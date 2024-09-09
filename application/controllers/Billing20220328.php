<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('billing/billing_model','billing');
        $this->load->library('form_validation');
    }

    public function index()
    {    
      $post = $this->input->post();
      $this->session->unset_userdata('bdp_search_data');
      $users_data = $this->session->userdata('auth_users');
      $data['doctor_list'] = $this->billing->get_transection_doctor();
      $data['sub_branch'] = $this->session->userdata('sub_branches_data');  
      $search_date = $this->session->userdata('search_data');
      $this->load->model('general/general_model'); 
      $data['payment_mode']=$this->general_model->payment_mode();
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
      $data['form_data'] = array('branch_id'=>'','doctor_id'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'pay_mode'=>'1');
      if(!empty($post))
      {
         $this->billing->doctor_branch_pay();
         ////////// Send SMS /////////////////////
         if(isset($post['branch_id']) && !empty($post['branch_id']))
          {
            $this->load->model('branch/branch_model','branch');
            $branch_data = $this->branch->get_by_id($post['branch_id']);
            $doctor_name = $branch_data['branch_name'];
            $mobile_no = $branch_data['contact_no'];
            $email = $branch_data['email'];
          }
          else if(isset($post['doctor_id']) && !empty($post['doctor_id']))
          {
              $doctor_data = get_doctor($post['doctor_id']);
              $doctor_name = $doctor_data->doctor_name;
              $mobile_no  = $doctor_data->mobile_no;
              $email = $doctor_data->email;
          }
        if(in_array('282',$users_data['permission']['action']))
        {
           
          if(!empty($mobile_no))
          { 
            $sms_balance = $post['balance']-$post['pay_amount'];
            send_sms('doctor_payment_received',9,$mobile_no,array('{name}'=>$doctor_name,'{payment}'=>$post['pay_amount'],'{balance}'=>$sms_balance,'{payment_date}'=>date('d-m-Y')));  
          }
        }
        //////////////////////////////////////////

        ////////// SEND EMAIL ///////////////////
        if(!empty($email))
          { 

            $sms_balance = $post['balance']-$post['pay_amount'];
            $this->load->library('general_functions'); 
            $this->general_functions->email($email,'','','','','','doctor_payment_received','9',array('{name}'=>$doctor_name,'{payment}'=>$post['pay_amount'],'{balance}'=>$sms_balance,'{payment_date}'=>date('d-m-Y'))); 
          }
        ////////////////////////////////////////


      }
          $data['page_title'] = 'Branch / Doctor Payment'; 
          $this->load->view('billing/branch_doctor_payment',$data);
    }
     

    public function bdp_type_html($vals="")
    { 
      $html = "";
      if($vals==1)
      {
        $doctor_list = $this->billing->get_transection_doctor(); 
        $html .= '<select name="doctor_id" id="doctor_id" onchange="return search_record(1,this.value);"><option value="">Select Doctor</option>';
        if(!empty($doctor_list))
        {
          foreach($doctor_list as $doctor)
          {
             $html .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
          }
        }
        $html .= '</select>';

      }
      else
      {
        $sub_branch = $this->session->userdata('sub_branches_data');  
        $html .= '<select name="branch_id" id="branch_id" onchange="return search_record(0,this.value);">'; 
        if(!empty($sub_branch))
        { 
          $html .= '<option value="">Select Branch</option> ';
          foreach($sub_branch as $branch)
          {
             $html .= '<option value="'.$branch["id"].'">'.$branch["branch_name"].'</option>';
          }
        } 
        $html .= '</select>';
      }
      echo $html; 
    }

    public function search_record()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
          $branch_id = 0;
          $doctor_id = 0;
          
          if(isset($post['doctor_id']) && !empty($post['doctor_id']))
          {
            $doctor_id = $post['doctor_id'];
            $branch_id = '';
          }
          else if(isset($post['branch_id']) && !empty($post['branch_id']))
          {
            $branch_id = $post['branch_id'];
            $doctor_id = '';
          }

          $search_data =  array(
                                 'branch_id'=>$branch_id,
                                 'doctor_id'=>$doctor_id,
                                 'start_date'=>$post['start_date'],
                                 'end_date'=>$post['end_date']
                                  
                               );  
      		 //$post = $this->input->post();
           
      	   $payment_data = $this->billing->branch_doctor_payment($search_data); 
           //print_r($payment_data);die; 
           $table = '   <thead class="bg-theme">
                            <tr>  
                							<th> Doctor/Branch name </th> 
                							<th> Amount To be Received </th>  
                							<th> Recieved Amount </th> 
                						</tr>
                				</thead>';
            //print_r($payment_data); exit; 
                       
            if(!empty($payment_data))
            {
              $total_debit = '0.00';
              if($payment_data[0]->debit_payment>0)
              {
                $total_debit = $payment_data[0]->debit_payment;
              }
              $total_credit='0.00';
              if($payment_data[0]->credit_payment>0)
              {
                $total_credit = $payment_data[0]->credit_payment;
              }
              $table .= '<tr>
                       <td>'.$payment_data[0]->name.'</td>
                 <td>'.$total_credit.'</td>
                 <td>'.$total_debit.'</td>
              </tr>';
            
            }
				  $balance = $total_credit - $total_debit;
          $response = array('data'=>$table, 'total_due'=>$total_credit, 'rec_amount'=>$total_debit, 'balance'=>$balance); 
          
        }
        else
        {
          $table .= '<tr><td colspan="3" align="center">Record not found</td></tr>';
          $response = array('data'=>$table,  'total_due'=>'','rec_amount'=>'', 'balance'=>'');	
        }
  		 
  		 
  		 echo json_encode($response,true);
   } 

    public function db_comission_list()
  	{
  	   $get = $this->input->get();
  	   if(!empty($get))
  	   {
          if($get['type']==1)
          {
            $data['doctor_name'] = get_doctor_name($get['ids']); 
          }
  	      $data['page_title'] = 'Comission List';
  	      $data['list'] = $this->billing->db_comission_list($get);
  		    $this->load->view('billing/db_comission_list',$data);
  	   }
  	}

    public function doctor_paid_commission_list()
    {
        $get = $this->input->get();
         if(!empty($get))
         {
            $data['page_title'] = 'Payment Details';
            $data['list'] = $this->billing->doctor_paid_comission_list($get);
          $this->load->view('billing/doctor_paid_commission_list',$data);
         } 
    }
	
	public function db_detail_list()
	{
	   $get = $this->input->get();
	   if(!empty($get))
	   {
	      $data['page_title'] = 'Payment Details';
	      $data['list'] = $this->billing->db_detail_list($get);
		  $this->load->view('billing/db_detail_list',$data);
	   }
	}
    

    public function reset_comission_search()
    {
       $search_data = $this->session->userdata('bdp_search_data');
       $new_search_data = array('doctor_id'=>$search_data['doctor_id'], 'branch_id'=>$search_data['branch_id']);
       $this->session->set_userdata('bdp_search_data', $new_search_data);
    }

    public function reset_date_search()
    {
       $search_data = $this->session->userdata('bdp_search_data');
       $new_search_data = array('doctor_id'=>$search_data['doctor_id'], 'branch_id'=>$search_data['branch_id']);
       $this->session->set_userdata('bdp_search_data', $new_search_data);
    }    

    public function doctor_commission()
    { 
      //echo 'rwer';die;
      $users_data = $this->session->userdata('auth_users');
      $this->load->model('doctors/doctors_model','doctors');
      $post = $this->input->post();
      $data['page_title'] = 'Doctor Commission';

      if($users_data['users_role']==3)
      {
            $doctor_id = $users_data['parent_id'];
            $this->load->model('branch/branch_model');
            $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
            $branch_id = $doctor_data[0]->branch_id; 
            $data['doctor_list'] = $this->billing->commission_doctor_list($branch_id);
      }
      else
      {
           $data['doctor_list'] = $this->billing->commission_doctor_list($users_data['parent_id']);
      }

        $data['branch_list'] = $this->session->userdata('sub_branches_data');
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

      $data['form_data'] = array('from_date'=>$start_date, 'to_date'=>$end_date,'pay_mode'=>'1'); 
      if(!empty($post))
      {
        //print_r($post); exit;
        $this->billing->pay_doctor_commission();
        $doctor_details = $this->doctors->get_by_id($post['doctor_id']);
        //print_r($get_by_id_data);
        $doctor_name = $doctor_details['doctor_name'];
        $mobile_no = $doctor_details['mobile_no'];
        $email = $doctor_details['email'];
        //check permission
        if(in_array('640',$users_data['permission']['action']))
        {
          if(!empty($mobile_no))
          {
            $pay_amount = $post['pay_amount'];
            send_sms('doctor_payment',11,$doctor_name,$mobile_no,array('{Name}'=>$doctor_name,'{Amt}'=>$post['pay_amount'])); 
          }
        }
        //check email permission
        if(in_array('641',$users_data['permission']['action']))
        {
        if(!empty($email))
        {
          
          $this->load->library('general_functions');
          $this->general_functions->email($email,'','','','','1','doctor_payment','11',array('{Name}'=>$doctor_name,'{Amt}'=>$post['pay_amount']));
           
        }
        }
        return false;
      }

      $data['branch_id']='';
      $users_data = $this->session->userdata('auth_users'); 
      if($users_data['users_role']==3)
      {
        $doctor_id = $users_data['parent_id'];
        $this->load->model('branch/branch_model');
        $doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
        $data['branch_id']= $doctor_data[0]->branch_id; 
      } 
      $data['vendor_list'] = $this->billing->vendor_list();
      $this->load->model('general/general_model');
      $data['expense_category_list'] = $this->general_model->expense_category_list();
      $data['payment_mode']=$this->general_model->payment_mode();
      $data['dept_list']=get_expense_department(16);
      $this->load->view('billing/doctor_comission',$data);
    }
  

    public function get_commission_doctor($bid="")
    {
       $child_branch = $this->session->userdata('sub_branches_data');
       $childs = array_column($child_branch, 'id');
       if(!empty($bid))
       {
          $doctor_list = $this->billing->commission_doctor_list($bid);
          $dropdown = '<option value="">Select Doctor</option>';
          foreach($doctor_list as $doctor)
          {
            $dropdown .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
          }
          echo $dropdown;
       }
    }

    public function total_doctor_comission()
    {
       
      
      $post = $this->input->post();
      if(!empty($post) && !empty($post))
      {
        
        //echo "<pre>";print_r($commission_list); exit;
        $html = '<tr>
        <td colspan="3" align="center" class="text-danger"><div class="text-center">No record found.</div></td>
        </tr> ';
        $total_credit = '0.00';
        $total_debit = '0.00';
        $balance = '0.00'; 
        if(!empty($post) && !empty($post))
        { 
          
          $commission_list = $this->billing->total_doctor_comission($post);
          //print_r($commission_list);die;
          if(!empty($commission_list))
          {
              $total_credit = number_format(array_sum(array_column($commission_list, 'total_credit')),2,'.', ''); 
              //$total_debit = number_format($commission_list[0]['total_debit'],2,'.', ''); 
              //echo $total_credit; die;
              $debit_list = $this->billing->total_doctor_debit();
              if(!empty($debit_list))
              {
                $total_debit = number_format($debit_list[0]->total_debit,2,'.', '');
              }
              else
              {
                $total_debit = '0.00';
              }
              /*if(!empty($commission_list[0]['total_debit']))
              {
                 $total_debit = number_format($commission_list[0]['total_debit'],2,'.', '');
              }*/
              //echo $total_credit;die;
              $balance = $total_credit-$total_debit;
              $html = '<tr>
                        <td style="width:20%;text-align:left;">'.$commission_list[0]['doctor_name'].'</td>
                        <td>'.$total_credit.'</td>
                        <td>'.$total_debit.'</td>
                       </tr> ';
              
              }
        
        }
        //print_r($commission_list);die;

        $response_arr = array('html'=>$html, 'total_due'=>$total_credit,'total_debit'=>number_format($total_debit,2, '.', ''), 'balance'=>number_format($balance,2, '.', ''));
        
        
        echo json_encode($response_arr,true); 
      }
    
    }

    public function doctor_commission_details()
    {
       $get = $this->input->get();
       if(!empty($get))
       {
          $data['page_title'] = 'Doctor Comission Details';
          $data['list'] = $this->billing->doctor_commission_details($get);
          //print_r($data['list']); exit;
        $this->load->view('billing/doctor_commission_details',$data);
       }
    }
    public function doctor_commission_excel()
    {
       
       $get = $this->input->get();
       if(!empty($get))
       {
           
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $data_patient_reg = get_setting_value('PATIENT_REG_NO');
          // Field names in the first row
          $fields = array('Bill No.','Patient Name','Date','Department','Bill Amt.','Commission');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          
          $doctor_commission_list = $this->billing->doctor_commission_details($get);
          
          $rowData = array();
          $data= array();
          $ttl_amt=0;
          if(!empty($doctor_commission_list))
          {
               $i=0;
               foreach($doctor_commission_list as $commission_list)
               {
                   $ttl_amt=$ttl_amt+$commission_list->total_credit;
                    array_push($rowData,$commission_list->patient_code, $commission_list->patient_name,date('d-m-Y',strtotime($commission_list->booking_date)),$commission_list->commission_type,$commission_list->bill_amount,number_format($commission_list->total_credit,2));
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
          
          //echo "<pre>";print_r($data); exit; 
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
               //$objPHPExcel->setActiveSheetIndex(0);
               //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
               
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$ttl_amt);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':E'.$row.'')->getFont()->setBold( true );  
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=doctor_commission_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
          
	     
          
          
        
       } 
        
    }
    public function balance_clearance()
    {     $data['page_title']='Balance Clearance';
          $post = $this->input->post();
          $data['patient_to_balclearlist'] = $this->billing->patient_to_balclearlist();
          $table = '';
          if(isset($post) && !empty($post))
          {
             if(!empty($data['patient_to_balclearlist']))
             {
              foreach($data['patient_to_balclearlist'] as $patient_data)
              {
                if($patient_data['balance']>0)
                {
                   $table .= '<tr>
                          <td>'.$patient_data['patient_code'].'</td>
                          <td>'.$patient_data['patient_name'].'</td> 
                          <td>'.$patient_data['balance'].'</td>
                          <td width="200px"><button type="button" class="btn-custom" name="pay_now" id="pay_now" onclick="
                          pay_now_to_branch('.$patient_data["id"].','.$patient_data['balance'].');">Pay Now</button>
                          <button type="button" class="btn-custom" name="pay_now" id="pay_now" onclick="                        patient_balance_details('.$patient_data["id"].');">Details</button> 
                          </td>
                       </tr>';
                }  
                else
                {
                      $table = '<tr>
                          <td colspan="4" style="color:red; text-align:center;">Record not found.</td> 
                         </tr>';
                }
              }
             }
             else
             {
               $table = '<tr>
                          <td colspan="4" style="color:red; text-align:center;">Record not found.</td> 
                         </tr>';
             }
             
              echo $table;
              return false;         
          }
          $this->load->view('billing/balance_clearance',$data);
    }
    public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        // if($users_data['users_role']==2){
                $dropdown = '';
               if(!empty($sub_branch_details)){
                   $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" onchange="return get_balance_clearance_list(this.value);" ><option value="'.$users_data['parent_id'].'">Self</option>';
                 
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
    public function pay_now($patient_id='',$branch_id="")
    {

          $data['page_title'] = 'Balance Clearance';
          $post = $this->input->post(); 
          $data['form_error'] = [];
          $data['form_data'] = array(
                    'data_id'=>$patient_id,
                    'payment_mode'=>1,
                    'bank_name'=>'',
                    'transection_no'=>'',
                    'cheque_no'=>'',
                    'card_no'=>'',
                 );
          if(!empty($patient_id))
          {
             $result = $this->billing->patient_to_balclearlist($patient_id); 
             
             if(isset($result[0]['balance']) && $result[0]['balance']>0)
             { 
                 $data['form_data']['balance']=$result[0]['balance'];
                 
             }
             else if(isset($result[0]['balance']) && $result[0]['balance']<0)
             { 
                   $balance =  str_replace('-', '',$result[0]['balance']); 
                   $data['form_data']['balance']=$balance;
             }
             else
             {
                   $data['form_data']['balance']='';
             }

          }
       
          if(isset($post) && !empty($post))
          {
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                $pay_id = $this->billing->payment_to_branch($branch_id);
                echo $pay_id;
                return false;                
              }
              else
              {
                  $data['form_error'] = validation_errors();  
              }  
          } 
          $this->load->view('billing/pay_patient_to_branch',$data); 
    }
    
    public function print_patient_balance_receipt($id="")
    {
        $data['page_title'] = "Print Balance Clearance";
        $test_booking_id= $id ;
        $get_by_id_data = $this->billing->patient_balance_receipt_data($test_booking_id);
        $this->load->model('test/test_model','test');
        $template_format = $this->test->template_format(array('section_id'=>3,'types'=>2));
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        //print '<pre>';print_r($data['all_detail']); exit;
        //print '<pre>';print_r($data['all_detail']); exit;
        $this->load->view('billing/print_test_template',$data);
    }

    public function pathology_print_patient_balance_receipt($id="")
    {
        $data['page_title'] = "Print Balance Clearance";
        $test_booking_id= $id ;
        $get_by_id_data = $this->billing->pathology_patient_balance_receipt_data($test_booking_id);
        //print '<pre>';print_r($test_booking_id); exit;
        $this->load->model('test/test_model','test');
        $template_format = $this->test->template_format(array('section_id'=>3,'types'=>2));
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        //print '<pre>';print_r($data['all_detail']); exit;
        //print '<pre>';print_r($test_booking_id); exit;
        $this->load->view('billing/pathology_print_test_template',$data);
    }

    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('balance', 'balance', 'trim|required|numeric'); 
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required'); 
        if($post['payment_mode']==3)
        {
           $this->form_validation->set_rules('card_no', 'card no.', 'trim|required|numeric');
        }
        if($post['payment_mode']==4)
        {
          $this->form_validation->set_rules('transection_no', 'transection no.', 'trim|required'); 
        }
        if($post['payment_mode']==2)
        {
          $this->form_validation->set_rules('bank_name', 'bank name', 'trim|required'); 
          $this->form_validation->set_rules('cheque_no', 'cheque no.', 'trim|required|numeric'); 
        } 
         
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'balance'=>$post['balance'],
                                        'payment_mode'=>$post['payment_mode'],
                                        'bank_name'=>$post['bank_name'],
                                        'transection_no'=>$post['transection_no'],
                                        'cheque_no'=>$post['cheque_no'],
                                        'card_no'=>$post['card_no'],
                                       ); 
            return $data['form_data'];
        }   
    }

    public function patient_balance_details($bid="",$pid="")
    {
      if(!empty($bid) && !empty($pid))
      {
        $data['page_title'] = 'Doctor Comission Details';
        $data['list'] = $this->billing->patient_balance_details($bid,$pid); 
        $this->load->view('billing/patient_balance_details',$data);
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
        $html.='<div class="col-sm-4">
                            <div class="row m-b-5">
                                <div class="col-sm-5"><label>'.$payment_detail->field_name.'</label><span class="star">*</span></div>
                                <div class="col-sm-7"><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();" required><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div></div>
                           
                        </div>';
        }
        echo $html;exit;

    }



    public function hospital_commission()
    { 
      //echo 'rwer';die;
    	//$users_data = $this->session->userdata('auth_users');
    	//echo "<pre>"; print_r($users_data); exit;
      unauthorise_permission('174','1012');
      $users_data = $this->session->userdata('auth_users');
      $this->load->model('hospital/hospital_model','hospital');
      $post = $this->input->post();
      $data['page_title'] = 'Hospital Commission';

      if($users_data['users_role']==3)
      {
            $hospital_id = $users_data['parent_id'];
            $this->load->model('branch/branch_model');
            $hospital_data= $this->branch_model->get_hospital_branch($hospital_id);  
            $branch_id = $hospital_data[0]->branch_id; 
            $data['hospital_list'] = $this->billing->commission_hospital_list($branch_id);
      }
      else
      {
           $data['hospital_list'] = $this->billing->commission_hospital_list($users_data['parent_id']);
      }

      $data['branch_list'] = $this->session->userdata('sub_branches_data');

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
      $data['form_data'] = array('from_date'=>$start_date, 'to_date'=>$end_date,'pay_mode'=>'1'); 
      if(!empty($post))
      {
        //print_r($post); exit;
        $this->billing->pay_hospital_commission();


        $hospital_details = $this->hospital->get_by_id($post['hospital_id']);
        //print_r($get_by_id_data);
        $hospital_name = $hospital_details['hospital_name'];
        $mobile_no = $hospital_details['mobile_no'];
        $email = $hospital_details['email'];
        //check permission
        if(in_array('640',$users_data['permission']['action']))
        {
          if(!empty($mobile_no))
          {
            $pay_amount = $post['pay_amount'];
            send_sms('hospital_payment',11,$hospital_name,$mobile_no,array('{Name}'=>$hospital_name,'{Amt}'=>$post['pay_amount'])); 
          }
        }
        //check email permission
        if(in_array('641',$users_data['permission']['action']))
        {
        if(!empty($email))
        {
          
          $this->load->library('general_functions');
          $this->general_functions->email($email,'','','','','1','hospital_payment','11',array('{Name}'=>$hospital_name,'{Amt}'=>$post['pay_amount']));
           
        }
        }
        return false;
      }

      $data['branch_id']='';
      $users_data = $this->session->userdata('auth_users'); 
      if($users_data['users_role']==3)
      {
        $hospital_id = $users_data['parent_id'];
        $this->load->model('branch/branch_model');
        $hospital_data= $this->branch_model->get_hospital_branch($hospital_id);  
        $data['branch_id']= $hospital_data[0]->branch_id; 
      } 
        $this->load->model('general/general_model');
      $data['payment_mode']=$this->general_model->payment_mode();
      $this->load->view('billing/hospital_comission',$data);
    }

    public function total_hospital_comission()
    {
      
      $post = $this->input->post();
      if(!empty($post) && !empty($post))
      {
        
        //echo "<pre>";print_r($commission_list); exit;
        $html = '<tr>
        <td colspan="3" align="center" class="text-danger"><div class="text-center">No record found.</div></td>
        </tr> ';
        $total_credit = '0.00';
        $total_debit = '0.00';
        $balance = '0.00'; 
        if(!empty($post) && !empty($post))
        { 
          
          $commission_list = $this->billing->total_hospital_comission($post);
          if(!empty($commission_list))
          { 
              if(!empty($commission_list[0]['total_credit']))
              {
                $total_credit = number_format(array_sum(array_column($commission_list, 'total_credit')),2,'.', '');
              } 
              /*if(!empty($commission_list[0]['total_debit']))
              {
                 $total_debit = number_format($commission_list[0]['total_debit'],2,'.', '');
              }*/
              $debit_list = $this->billing->total_hospital_debit();
              if(!empty($debit_list))
              {
                $total_debit = number_format($debit_list[0]->total_debit,2,'.', '');
              }
              else
              {
                $total_debit = '0.00';
              }
              $balance = $total_credit-$total_debit;
              $html = '<tr>
                        <td style="width:20%;text-align:left;">'.$commission_list[0]['hospital_name'].'</td>
                        <td>'.$total_credit.'</td>
                        <td>'.$total_debit.'</td>
                       </tr> ';
              
              }
        
        }

        $response_arr = array('html'=>$html, 'total_due'=>$total_credit,'total_debit'=>number_format($total_debit,2, '.', ''), 'balance'=>number_format($balance,2, '.', ''));
        
        
        echo json_encode($response_arr,true); 
      }
    }

    public function hospital_commission_details()
    {
       $get = $this->input->get();
       if(!empty($get))
       {
          $data['page_title'] = 'Hospital Comission Details';
          $data['list'] = $this->billing->hospital_commission_details($get);
          //echo "<pre>";  print_r($data['list']);die;
          $this->load->view('billing/hospital_commission_details',$data);
       }
    }

    public function hospital_paid_commission_list()
    {
        $get = $this->input->get();
         if(!empty($get))
         {
            $data['page_title'] = 'Payment Details';
            $data['list'] = $this->billing->hospital_paid_comission_list($get);
          $this->load->view('billing/hospital_paid_commission_list',$data);
         } 
    }

    public function get_commission_hospital($bid="")
    {
       $child_branch = $this->session->userdata('sub_branches_data');
       $childs = array_column($child_branch, 'id');
       if(!empty($bid))
       {
          $hospital_list = $this->billing->commission_hospital_list($bid);
          $dropdown = '<option value="">Select Hospital</option>';
          foreach($hospital_list as $hospital)
          {
            $dropdown .= '<option value="'.$hospital->id.'">'.$hospital->hospital_name.'</option>';
          }
          echo $dropdown;
       }
    }

    public function reset_hospital_comission_search()
    {
       $search_data = $this->session->userdata('bdp_hospital_search_data');
       $new_search_data = array('hospital_id'=>$search_data['hospital_id'], 'branch_id'=>$search_data['branch_id']);
       $this->session->set_userdata('bdp_hospital_search_data', $new_search_data);
    }
    
       /* letter head print */

function print_doctor_commission_letter_head()
{

    $get=$this->input->get();
    if(!empty($get))
    {
      $data['page_title'] = 'Doctor Comission Details';
      $list = $this->billing->doctor_commission_details($get);
    }
      
      $this->load->library('m_pdf');
      $this->load->model('general/general_model');
      $branch_id=$get['branch_id'];
      $users_data = $this->session->userdata('auth_users'); 
      $template_format= $this->billing->letterhead_template_format($branch_id);
      $template_data=$template_format;
      $header_replace_part=$template_data->page_header;
      $doctor = get_doctor_name($_GET['doctor_id']);
     $printer_id=$_GET['printer_id'];
     $start_date=$_GET['start_date'];
     $end_date=$_GET['end_date'];

     $users_data = $this->session->userdata('auth_users'); 
     $data= get_setting_value('PATIENT_REG_NO');


      $header_replace_part=str_replace("{doctor_name}",$doctor,$header_replace_part);
      $header_replace_part=str_replace("{start_date}",$start_date,$header_replace_part);
      $header_replace_part=str_replace("{end_date}",$end_date,$header_replace_part);

    $table_data='
        <table border=1 cellpadding="8" width="100%" style="font:13px Arial;border-collapse:collapse;">
          <tr style="font-weight:bold;">
                <th width="40px">Bill No.</th>
                <th align="left"> Patient Name</th>
                <th align="left"> Date</th> 
                <th width="100px" align="left">Department</th>
                <th align="left">Bill Amt.</th>
                <th align="right">Commission</th>
              </tr> ';
 
 if(!empty($list))
 {
    
    $total_amount = '0';
    foreach($list as $data)
    {
    
    $table_data.='<tr>
        <td>'.$data->patient_code.'</td>
        <td>'.$data->patient_name.'</td>
        <td align="left">'.date('d-m-Y',strtotime($data->booking_date)).'</td> 
        <td align="left">'. $data->commission_type.'</td>
        <td align="left">'. $data->bill_amount.'</td>
        <td align="right">'.number_format($data->total_credit,2).'</td>
        </tr>';


    $total_amount = $total_amount+ $data->total_credit;
     }

     $table_data.='<tr> 
          <th colspan="5" align="right">Total Amount:</th>
          <th align="right">'.number_format($total_amount,2).'</th> 
       </tr>';
    
   }
   else
   {
    
      $table_data.='<tr>
        <td align="center" colspan="5">Record not found</td>
       </tr>';

   }
 
   $table_data.='</table>';
                        
  $middle_replace_part=$template_data->page_middle;
  $middle_replace_part=str_replace("{table_data}",$table_data,$middle_replace_part);
  $footer_data_part = $template_data->page_footer;
  $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
  $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
  if($type=='Download')  
  {
        if($template_data->header_pdf==1)
        {
          $page_header =$header_replace_part;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$page_header.'</div>';
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
        if($template_data->header_print==1)
        {
           $page_header =$header_replace_part;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$page_header.'</div><br></br>';
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

    public function test_details()
    {
       $get = $this->input->get();
       if(!empty($get))
       {
            $data['page_title'] = 'Test Reports';
            //$data['list'] = $this->billing->test_details($get);
            
            
            $data['dtl_test_list'] = $this->billing->details_test_list($get);
            $data['total_entities'] = $this->billing->details_test_total_entities($get);
            //print_r($data['dtl_test_list']); exit;
            $this->load->view('billing/test_details',$data);
       }
    }

   
     
}
?>