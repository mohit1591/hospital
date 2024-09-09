<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_booking extends CI_Controller {
 
  	function __construct() 
  	{
    		parent::__construct();	
    		auth_users();  
    		$this->load->model('ipd_booking/ipd_discharge_booking_model','ipd_booking');
    		$this->load->library('form_validation');
    }

    
    public function index()
    {
        unauthorise_permission(121,733);
        //$this->session->unset_userdata('ipd_booking_id'); 
        $this->session->unset_userdata('net_values_all');
        $this->session->unset_userdata('ipd_particular_charge_billing');
        $this->session->unset_userdata('ipd_particular_payment');
        $this->session->unset_userdata('ipd_advance_payment');
        
        $data['page_title'] = 'IPD Discharge Patient List'; 
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
        $data['form_data'] = array('patient_name'=>'','patient_type'=>'','mobile_no'=>'','ipd_no'=>'','mobile_no'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'running'=>1); 
        $this->load->view('ipd_booking/discharge_list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(121,733);
        $list = $this->ipd_booking->get_datatables();  
        //echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $del = ',';
        $grand_total_amount =0;
        $grand_total_discount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0; 
        foreach ($list as $ipd_booking) 
        { 
            $no++;
            $row = array();

            
            //$grand_paid_amount = $grand_paid_amount + $ipd_booking->ipd_paid_amount;
            
            $ipd_refund_amount='0';
            if($ipd_booking->ipd_refund_amount>0)
            {
              $ipd_refund_amount = $ipd_booking->ipd_refund_amount;
            }
            
            $grand_paid_amount = $grand_paid_amount + $ipd_booking->ipd_paid_amount-$ipd_refund_amount;

            /*if($ipd_booking->ipd_total_amount>0)
            {
               $ipd_total_amount  = $ipd_booking->ipd_total_amount;
               
            }
            else
            {
               $ipd_total_amount  = $ipd_booking->total_amount_dis_bill; //discharge case
               
            }*/
            
            $ipd_total_amount  = $ipd_booking->total_amount_dis_bill;
            $grand_total_amount = $grand_total_amount+$ipd_total_amount;

            if($ipd_booking->ipd_balance_amount<0)
            {
               $ipd_balanceAmount = '0.00';
            }
            else
            {
                //$ipd_balanceAmount = $ipd_booking->ipd_balance_amount;

                 $pay_amt = $ipd_booking->ipd_paid_amount-$ipd_refund_amount;
                 $ipd_balanceAmount = $ipd_total_amount-($pay_amt+$ipd_booking->discount_amount_dis_bill);
            }

            $grand_balance_amount = $grand_balance_amount + $ipd_balanceAmount ;
            $grand_total_discount = $grand_total_discount+$ipd_booking->discount_amount_dis_bill;
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            }          
            $row[] = '<input type="checkbox" name="ipd_booking[]" class="checklist" value="'.$ipd_booking->id.'">'; 
            $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
            $row[] = $ipd_booking->discharge_bill_no; 
            $row[] = $ipd_booking->ipd_no;
            $row[] = $ipd_booking->patient_code;
            $row[] = $ipd_booking->patient_name;
            //$row[] = $purchase->total_amount;
            $row[] = $ipd_booking->mobile_no;
            $age_y = $ipd_booking->age_y;
                $age_m = $ipd_booking->age_m;
                $age_d = $ipd_booking->age_d;
                $age_h = $ipd_booking->age_h;
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
            $row[] = $age;
            $row[] = $gender[$ipd_booking->gender];
            $row[] = date('d-m-Y',strtotime($ipd_booking->admission_date)).' '.date('h:i A',strtotime($ipd_booking->admission_time));
            
            
            $row[] = date('d-m-Y h:i A',strtotime($ipd_booking->discharge_date));
            
            //$ipd_booking->admission_date;
            


            /*if($ipd_booking->ipd_total_amount>0)
            {
              $ipd_total_amount  = $ipd_booking->ipd_total_amount;
            }
            else
            {
              $ipd_total_amount  = $ipd_booking->total_amount_dis_bill;
            }*/
            
            
            $ipd_total_amount  = $ipd_booking->total_amount_dis_bill; //discharge case
            $discount_amount = $ipd_booking->discount_amount_dis_bill;
            //$ipd_paid_amount = $ipd_booking->ipd_paid_amount;
            $row[] = $ipd_total_amount;
            $row[] = $discount_amount;
            //$row[] = $ipd_booking->paid_amount_dis_bill;
           
            //$row[] = $ipd_booking->ipd_paid_amount;
           $IPD_refund_amount='0';
            if($ipd_booking->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $ipd_booking->ipd_refund_amount;
            }
            $paid_amounts = $ipd_booking->ipd_paid_amount-$IPD_refund_amount;
            if(!empty($paid_amounts))
            {
              $paid_amounts = number_format($paid_amounts,2);
            }
            $row[] = $paid_amounts;

            if($ipd_booking->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
             // $ipdBalanceAmount = $ipd_booking->ipd_balance_amount;
              $pay_amt = $ipd_booking->ipd_paid_amount-$IPD_refund_amount;
              $ipdBalanceAmount = $ipd_total_amount-($pay_amt+$discount_amount);
              $ipdBalanceAmount = number_format($ipdBalanceAmount,2);
            }
            $row[] = $ipdBalanceAmount ;
            if($ipd_booking->patient_type==1)
            {
              $patient_type='Normal';
            }
            else if($ipd_booking->patient_type==2)
            {
              $patient_type='Panel';
            }
            $row[] = $patient_type;  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
            
            $btn_admission_print='';
            $btndischarge_bill="";
            $btn_download_image='';
            $btndischarge_summary='';
            $btn_download_image_admission='';
              
            $print_pdf_url = "'".base_url('ipd_booking/print_ipd_booking_recipt/'.$ipd_booking->id)."'";
            
            $print_admission_pdf_url = "'".base_url('ipd_booking/print_ipd_adminssion_card/'.$ipd_booking->id)."'";
            
             
            if(in_array('750',$users_data['permission']['action']))
            { 

            //$btndischarge_summary = ' <a class="btn-custom"  href='.base_url('ipd_patient_discharge_summary/add/'.$ipd_booking->id.'/'.$ipd_booking->patient_id).' title="Discharge Summary" data-url="512"> <i class="fa fa-bed" aria-hidden="true"></i> Discharge Summary</a> ';
            }
            $btndisprogress_report=''; 
            if(in_array('743',$users_data['permission']['action']))
            {
              //$btndisprogress_report = ' <a class="btn-custom"  href='.base_url('ipd_progress_report/add/'.$ipd_booking->patient_id.'/'.$ipd_booking->id).' title="Add Progress Report" data-url="512"> <i class="fa fa-file-text-o" aria-hidden="true"></i> Add Progress Note</a> ';
            }
            $btn_print_progress='';

            $print_discharge_url = "'".base_url('ipd_discharge_bill/print_discharge_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";
            $btn_print_discharge = ' <a class="btn-custom" onClick="return print_window_page('.$print_discharge_url.')" href="javascript:void(0)" title="Print Discharge Bill"  data-url="512"> <i class="fa fa-print" aria-hidden="true"></i>  Print Discharge Bill</a>';
            
          
      


       //$btn_download_image = ' <a class="btn-custom" href="'.base_url('/ipd_booking/download_image/'.$ipd_booking->id.'/'.$ipd_booking->branch_id).'" title="Download Image" data-url="512" target="_blank"><i class="fa fa-download"></i> Download Image</a>';

       //$btn_download_image_admission = ' <a class="btn-custom" href="'.base_url('/ipd_booking/download_image_admission/'.$ipd_booking->id.'/'.$ipd_booking->branch_id).'" title="Download Image" data-url="512" target="_blank"><i class="fa fa-download"></i> Image</a>';
        
       $btnprint = '<a class="btn-custom" onclick="print_window_page('.$print_pdf_url.');" target="_blank"><i class="fa fa-print"></i> Print</a>';
          $btn_list = ' 
            <div class="dropdown">
            <a class="btn-custom toggle" data-toggle="dropdown" onClick="" href="javascript:void(0)"><i class="fa fa-download"></i> Download Receipt</a>
           <div class="dropdown-menu">
          '.$btnprint.$btn_download_image.'
          </div>
          </div>';
          $btn_admission_print = '<a class="btn-custom" onclick="print_window_page('.$print_admission_pdf_url.');" target="_blank"><i class="fa fa-print"></i> Print Admission</a>';
           $btn_list_admission = ' 
            <div class="dropdown">
            <a class="btn-custom toggle" data-toggle="dropdown" onClick="" href="javascript:void(0)"><i class="fa fa-download"></i> Download Admission</a>
           <div class="dropdown-menu">
          '.$btn_admission_print.$btn_download_image_admission.'
          </div>
          </div>';
        $print_summarized_url = "'".base_url('ipd_discharge_bill/print_summarized_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";

        $print_consolidated_bill = "'".base_url('ipd_discharge_bill/print_consolidated_bill/'.$ipd_booking->id.'/'.$ipd_booking->patient_id)."'";

        $ipd_consolidated_bill= ' <a class="btn-custom" onClick="return print_window_page('.$print_consolidated_bill.')" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-plus"></i> Consolidated Bill</a> ';

        $ipd_summarized_bill = ' <a class="btn-custom" onClick="return print_window_page('. $print_summarized_url.')" style="'.$ipd_booking->id.'" title="Test booking"><i class="fa fa-plus"></i> Print Summarize Bill</a> ';


              
            $btndischarge_bill='';
            if(in_array('785',$users_data['permission']['action']))
            {
              // $btndischarge_bill = ' <a class="btn-custom"  onclick="confirmation_box('.$ipd_booking->id.','.$ipd_booking->patient_id.');"  title="Discharge Bill" data-url="512"> <i class="fa fa-database" aria-hidden="true"></i> Modify Bill</a> ';
            }
            $btn_readmit='';
            if(in_array('785',$users_data['permission']['action']))
            {
              //$btn_readmit = ' <a class="btn-custom"  onclick="confirmation_readmit('.$ipd_booking->id.','.$ipd_booking->patient_id.');"  title="Re-admit" data-url="512"> <i class="fa fa-database" aria-hidden="true"></i> Re-admit</a> ';

            }
          $row[] = $btnview.$btndelete.$btndischarge_summary.$btndischarge_bill.$btndisprogress_report.$btn_print_progress.$btn_print_discharge.$btn_readmit.$ipd_consolidated_bill.$ipd_summarized_bill;
          

          $data[] = $row;
            $tot_row = array();
            if($i==$total_num)
            {
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = ''; 
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';

              $tot_row[] = '';
              $tot_row[] = '<input type="text" class="w-80px" style="text-align:right;" value='.number_format($grand_total_amount,2,'.','').' readonly >';

              $tot_row[] = '<input type="text" class="w-80px" style="text-align:right;" value='.number_format($grand_total_discount,2,'.','').' readonly >';
              

              $tot_row[] = '<input type="text" class="w-80px" style="text-align:right;" value='.number_format($grand_paid_amount,2,'.','').' readonly >'; 
              $tot_row[] = '<input type="text" class="w-80px" style="text-align:right;" value='.number_format($grand_balance_amount,2,'.','').' readonly >'; 
              $tot_row[] = '';
              $tot_row[] = '';  
              $data[] = $tot_row; 
           }
            $i++;
        }
        //print_r($data);
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_booking->count_all(),
                        "recordsFiltered" => $this->ipd_booking->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function download_image($id="",$branch_id='')
    {
      
      $data['type'] = 2;
      $data['download_type'] = '2'; //for image
      $data['page_title'] = "Print Bookings";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      elseif(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
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
      //print_r($template_format); exit;
      //Package
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('ipd_booking_receipt',$branch_id);
      $data['date_time_status'] = $get_date_time_setting->date_time_status;

      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      //$data['medicine_ids']=$medicine_arr;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
      $this->load->view('ipd_booking/print_ipd_reciept_template',$data);
    }

     public function download_image_admission($id="",$branch_id='')
    {
      
      $data['type'] = 2;
      $data['download_type'] = '2'; //for image
      $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
      $data['page_title'] = "Print Bookings";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
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

      $template_format = $this->ipd_admission_print->template_format(array('setting_name'=>'IPD_PRINT_SETTING','unique_id'=>1,'type'=>0),$branch_id);

      
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      $data['template_data']=$template_format->setting_value;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('ipd_admission_print');
      $data['date_time_status'] = $get_date_time_setting->date_time_status;

      $this->load->view('ipd_booking/print_ipd_admission_template',$data);
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
        $download  = REPORT_IMAGE_FS_IPD_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_IPD_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }
     public function save_image_admission()
    {
        $post = $this->input->post();
        //print_r($post); exit;
        $data = $post['data'];
        $patient_name = $post['patient_name'];
        $patient_code = $post['patient_code'];
        $data = substr($data,strpos($data,",")+1);
        $data = base64_decode($data);
        $file_name = strtolower($patient_name).'_'.strtolower($patient_code).'.png';
        $download  = REPORT_IMAGE_FS_IPD_PATH.$file_name;
        $file = DIR_UPLOAD_REPORT_IPD_IMAGE_PATH.$file_name;
        file_put_contents($file, $data);

        echo  $download;

    }

    public function reset_search()
    {
        $this->session->unset_userdata('ipd_discharge_booking_search');
    }



    public function ipd_booking_excel()
    {
        
		$this->load->library('excel');
		$this->excel->IO_factory();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
		$objPHPExcel->setActiveSheetIndex(0);
    $data_name = get_setting_value('PATIENT_REG_NO');    
        

        $fields = array('Bill No.','IPD No.',$data_name,'Patient Name','Mobile No.','Age','Gender','Admission','Discharge Date','Total','Discount','Paid Amount','Balance','Type');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        $list = $this->ipd_booking->search_report_data();
		
		$rowData = array();
		$data= array();
		if(!empty($list))
		{

			$i=0;
			foreach($list as $reports)
      {
           $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
           $age_y = $reports->age_y;
                $age_m = $reports->age_m;
                $age_d = $reports->age_d;
                $age_h = $reports->age_h;
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
           
            $admission_discharge = date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i A',strtotime($reports->admission_time)); //$ipd_booking->admission_date;
            
            $discharge_dates = date('d-m-Y H:i A',strtotime($reports->discharge_date));
            if($reports->patient_type==1)
            {
              $patient_type='Normal';
            }
            else if($reports->patient_type==2)
            {
              $patient_type='Panel';
            }
            
/*if($reports->ipd_total_amount>0)
            {
              $ipd_total_amount  = $reports->ipd_total_amount;
            }
            else
            {
              $ipd_total_amount  = $reports->total_amount_dis_bill;
            }*/
            $ipd_total_amount  = $reports->total_amount_dis_bill;
            $Total_amt = $ipd_total_amount;
            //$row[] = $reports->paid_amount_dis_bill;
            $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }

            //$paid_Amount = $reports->ipd_paid_amount-$IPD_refund_amount;
            /*if($reports->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
              $ipdBalanceAmount = $reports->ipd_balance_amount;
            }
            $Balance_amt = $ipdBalanceAmount;*/


            
            $discount_amount = $reports->discount_amount_dis_bill;
            //$ipd_paid_amount = $ipd_booking->ipd_paid_amount;
            
            $discount = $discount_amount;
            //$row[] = $ipd_booking->paid_amount_dis_bill;
           
            //$row[] = $ipd_booking->ipd_paid_amount;
           $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }
            $paid_amounts = $reports->ipd_paid_amount-$IPD_refund_amount;
            if(!empty($paid_amounts))
            {
              $paid_amounts = number_format($paid_amounts,2);
            }
            $paid_Amount = $paid_amounts;

            if($reports->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
             // $ipdBalanceAmount = $ipd_booking->ipd_balance_amount;
              $pay_amt = $reports->ipd_paid_amount-$IPD_refund_amount;
              $ipdBalanceAmount = $ipd_total_amount-($pay_amt+$discount_amount);
              $ipdBalanceAmount = number_format($ipdBalanceAmount,2);
            }
            $Balance_amt = $ipdBalanceAmount;


            array_push($rowData,$reports->discharge_bill_no,$reports->ipd_no,$reports->patient_code,$reports->patient_name,$reports->mobile_no,$age,$gender[$reports->gender],$admission_discharge,$discharge_dates,$Total_amt,$discount,$paid_Amount,$Balance_amt,$patient_type);
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
          header("Content-Disposition: attachment; filename=ipd_discharge_booking_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function ipd_booking_csv()
    {
        
		$this->load->library('excel');
		$this->excel->IO_factory();
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
		$objPHPExcel->setActiveSheetIndex(0);
        
     $fields = array('Bill No.','IPD No.',$data_name,'Patient Name','Mobile No.','Age','Gender','Admission','Discharge Date','Total','Paid Amount','Balance','Type');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        $list = $this->ipd_booking->search_report_data();
		
		$rowData = array();
		$data= array();
		if(!empty($list))
		{

			$i=0;
			foreach($list as $reports)
            {
                   $gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');
           $age_y = $reports->age_y;
                $age_m = $reports->age_m;
                $age_d = $reports->age_d;
                $age_h = $reports->age_h;
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
           
            $admission_discharge = date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i A',strtotime($reports->admission_time)); 
            $dicharge_dates = date('d-m-Y H:i A',strtotime($reports->discharge_date));
            //$ipd_booking->admission_date;
            if($reports->patient_type==1)
            {
              $patient_type='Normal';
            }
            else if($reports->patient_type==2)
            {
              $patient_type='Panel';
            }
            
$ipd_total_amount  = $reports->total_amount_dis_bill;
            $Total_amt = $ipd_total_amount;
            //$row[] = $reports->paid_amount_dis_bill;
            $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }

            //$paid_Amount = $reports->ipd_paid_amount-$IPD_refund_amount;
            /*if($reports->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
              $ipdBalanceAmount = $reports->ipd_balance_amount;
            }
            $Balance_amt = $ipdBalanceAmount;*/


            
            $discount_amount = $reports->discount_amount_dis_bill;
            //$ipd_paid_amount = $ipd_booking->ipd_paid_amount;
            
            $discount = $discount_amount;
            //$row[] = $ipd_booking->paid_amount_dis_bill;
           
            //$row[] = $ipd_booking->ipd_paid_amount;
           $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }
            $paid_amounts = $reports->ipd_paid_amount-$IPD_refund_amount;
            if(!empty($paid_amounts))
            {
              $paid_amounts = number_format($paid_amounts,2);
            }
            $paid_Amount = $paid_amounts;

            if($reports->ipd_balance_amount<0)
            {
              $ipdBalanceAmount = '0.00';//$ipd_booking->balance_amount_dis_bill;
            }
            else
            {
             // $ipdBalanceAmount = $ipd_booking->ipd_balance_amount;
              $pay_amt = $reports->ipd_paid_amount-$IPD_refund_amount;
              $ipdBalanceAmount = $ipd_total_amount-($pay_amt+$discount_amount);
              $ipdBalanceAmount = number_format($ipdBalanceAmount,2);
            }
            $Balance_amt = $ipdBalanceAmount;

                   array_push($rowData,$reports->discharge_bill_no,$reports->ipd_no,$reports->patient_code,$reports->patient_name,$reports->mobile_no,$age,$gender[$reports->gender],$admission_discharge,$dicharge_dates,$Total_amt,$discount,$paid_Amount,$Balance_amt,$patient_type);
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
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=ipd_discharge_booking_list_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }


    public function pdf_ipd_booking()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->ipd_booking->search_report_data();
        $this->load->view('ipd_booking/ipd_discharge_booking_html',$data);
        $html = $this->output->get_output();
        $this->load->library('pdf');
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ipd_discharge_booking_list_".time().".pdf");
    }

    public function print_ipd_booking()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->ipd_booking->search_report_data();
      $this->load->view('ipd_booking/ipd_discharge_booking_html',$data); 
    }
    public function advance_search()
    {

          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "patient_type"=>"",
                                      "patient_name"=>"",
                                      "ipd_no"=>"",
                                      "mobile_no"=>'',
                                      "room_no"=>'',
                                      "adhar_no"=>'',
                                      'attended_doctor'=>'',
                                      'running'=>0
                                       );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('ipd_discharge_booking_search', $marge_post);
          }
          $ipd_discharge_booking_search = $this->session->userdata('ipd_discharge_booking_search');
          if(isset($ipd_discharge_booking_search) && !empty($ipd_discharge_booking_search))
          {
              $data['form_data'] = $ipd_discharge_booking_search;
          }
          $this->load->view('ipd_booking/discharge_advance_search',$data);
   }

    public function add()
    {
        unauthorise_permission(121,734);
        $users_data = $this->session->userdata('auth_users');
        $pid='';

        if(isset($_GET['ipd']))
        {
          $pid= $_GET['ipd'];
        }
      
        $ipd_no = generate_unique_id(22);
        $this->load->model('general/general_model'); 
        $data['page_title'] = "IPD Booking";
        $data['form_error'] = [];
        $data['button_value'] = "Save";
        $post = $this->input->post();
        $vendor_id='';
        $age_m="";
        $age_y="";
        $age_d="";
        $address="";
        $address_second="";
        $address_third="";
        $gender=0;
        $vendor_code = "";
        $name = "";
        $patient_name = "";
        $mobile_no = "";
        $email = "";
        $address = "";
        $simulation_id="";
        $referral_doctor="";
        $adhar_no="";
        if($pid>0)
        {
           $patient = $this->ipd_booking->get_patient_by_id($pid);
           //print_r($purchase);
           if(!empty($patient))
           {
              $patient_id = $patient['id'];
              $simulation_id = $patient['simulation_id'];
              $patient_reg_code = $patient['patient_code'];
              $name = $patient['patient_name'];
              $age_m=$patient['age_m'];
              $age_d=$patient['age_d'];
              $age_y=$patient['age_y'];
              $address = $patient['address'];
              $address_second = $patient['address_second'];
              $address_third = $patient['address_third'];
              $mobile_no = $patient['mobile_no'];
              $email = $patient['patient_email'];
              $adhar_no = $patient['adhar_no'];
           }
        }
        else
        {
             $patient_id='';
             $patient_reg_code=generate_unique_id(4);
        }
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $this->load->model('opd/opd_model','opd');
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['assigned_d_by_id']='';
        $data['assigned_doctor'] = $this->ipd_booking->assigned_doctor_list();
        $data['panel_type_list'] = $this->general->panel_type_list();
        $data['panel_company_list'] = $this->general->panel_company_list();
        $data['package_list']=$this->general_model->ipd_package_list();
        $data['room_type_list']=$this->general_model->room_type_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list(); 
        $data['form_data'] = array(
                                    "patient_id"=>$patient_id,
                                    "data_id"=>"",
                                    "patient_reg_code"=>$patient_reg_code,
                                    "name"=>$name,
                                    'ipd_no'=>$ipd_no,
                                    'simulation_id'=>$simulation_id,
                                    "mobile"=>$mobile_no,
                                    "age_m"=>$age_m,
                                    "age_d"=>$age_d,
                                    "gender"=>$gender,
                                    "age_y"=>$age_y,
                                    'adhar_no'=>$adhar_no,
                                    "address"=>$address,
                                    "address_second"=>$address_second,
                                    "address_third"=>$address_third,
                                    "attended_doctor"=>'',
                                    "assigned_docotor_list"=>'',
                                    "authorization_amount"=>"0.00",
                                    "id_number"=>"",
                                    "room_id"=>"",
                                    "room_no_id"=>"",
                                    "time_unit"=>"",
                                    "bed_no_id"=>"",
                                    "patient_type"=>1,
                                    "package_id"=>"",
                                    "package"=>1,
                                    "remarks"=>"",
                                    "advance_deposite"=>"",
                                    "panel_type"=>"",
                                    "policy_number"=>"",
                                    "company_name"=>"",
                                    "admission_time"=>date('H:i:s'),
                                    "admission_date"=>date('d-m-Y'),
                                    'total_amount'=>"0.00",
                                    'discount_amount'=>"0.00",
                                    'payment_mode'=>"",
                                   // 'bank_name'=>"",
                                    //'card_no'=>"",
                                    //'cheque_no'=>"",
                                    //'cheque_date'=>'',
                                    'net_amount'=>"",
                                    'pay_amount'=>"",
                                    "field_name"=>'',
                                    //'transaction_no'=>"",
                                    "country_code"=>"+91",
                                    'mlc'=>'0',
                                    'referral_doctor'=>'',
                                    'referred_by'=>'',
                                    'referral_hospital'=>'',
                                    'discharge_date'=>'',
                                    );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $salesid=  $this->ipd_booking->save();

             
             //send sms
              if(!empty($salesid))
              {
                  $get_by_id_data = $this->ipd_booking->get_by_id($salesid);
                  $patient_name = $get_by_id_data['patient_name'];
                  $booking_code = $get_by_id_data['ipd_no'];
                  $paid_amount = $get_by_id_data['advance_deposite'];
                  $mobile_no = $get_by_id_data['mobile_no'];
                  $room_no = $get_by_id_data['room_no'];
                  $patient_email = $get_by_id_data['patient_email'];
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($mobile_no))
                    {
                      send_sms('ipd_booking',15,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{RoomNo}'=>$room_no));  
                    }
                    
                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($patient_email))
                  {
                    
                    $this->load->library('general_functions');
                    $this->general_functions->email($patient_email,'','','','','1','ipd_booking','15',array('{Name}'=>$patient_name,'{IPDNo}'=>$booking_code,'{RoomNo}'=>$room_no));
                     
                  }
                } 
              }


                $this->session->set_userdata('ipd_booking_id',$salesid);
                $this->session->set_flashdata('success','IPD booking has been successfully added.');
                redirect(base_url('ipd_booking/?status=print&admission_form=print_admission&mlc_status='.$_POST['mlc'].''));
                
            }
            else
            {
                $data['form_error'] = validation_errors(); 
                // print_r($data['form_error']);die;
            }    

        }
        $data['btn_name'] = 'Save';
       $this->load->view('ipd_booking/add',$data);
    }

    public function edit($id="")
    {
       unauthorise_permission(121,735);
      if(isset($id) && !empty($id) && is_numeric($id))
      {     
          $this->load->model('general/general_model');  
          $post = $this->input->post();
          $result = $this->ipd_booking->get_by_id($id); 
          //echo "<pre>";print_r($result); exit;
          $result_patient = $this->ipd_booking->get_patient_by_id($result['patient_id']);
          $data['simulation_list']= $this->general_model->simulation_list();
          $data['doctors_list']= $this->general_model->doctors_list();
          $this->load->model('opd/opd_model','opd');
          $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
          $data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
          $data['assigned_doctor'] = $this->ipd_booking->assigned_doctor_list();
          $data['assigned_d_by_id']=$this->ipd_booking->aasigned_doctor_by_id($result['id']);
          $data['panel_type_list'] = $this->general_model->panel_type_list();
          $data['panel_company_list'] = $this->general_model->panel_company_list();
          $data['package_list']=$this->general_model->ipd_package_list();
          $data['room_type_list']=$this->general_model->room_type_list($id);
          $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
          $this->load->model('general/general_model');
          $data['payment_mode']=$this->general_model->payment_mode();
          $get_payment_detail= $this->ipd_booking->payment_mode_detail_according_to_field($result['payment_mode'],$id);
          $total_values='';
          for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

          }
          $adhar_no ='';
          if(!empty($result_patient['adhar_no']))
          {
           $adhar_no = $result_patient['adhar_no'];
          }
          //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
          $data['page_title'] = "IPD Booking";  
          $data['button_value'] = "Update";
          $data['form_error'] = ''; 
          $discharge_date = '';
          if(!empty($result['discharge_date']) && $result['discharge_date']!='1970-01-01 00:00:00' && $result['discharge_date']!='0000-00-00 00:00:00') 
          {
            $discharge_date = date('d-m-Y',strtotime($result['discharge_date']));
          }
          
          $data['form_data'] = array( 
                                    "patient_id"=>$result['patient_id'],
                                    "data_id"=>$result['id'],
                                    "patient_reg_code"=>$result_patient['patient_code'],
                                    "name"=>$result_patient['patient_name'],
                                    'ipd_no'=>$result['ipd_no'],
                                    'referral_doctor'=>$result['referral_doctor'],
                                    'simulation_id'=>$result_patient['simulation_id'],
                                    "mobile"=>$result_patient['mobile_no'],
                                    "age_m"=>$result_patient['age_m'],
                                    "age_d"=>$result_patient['age_d'],
                                    "gender"=>$result_patient['gender'],
                                    "age_y"=>$result_patient['age_y'],
                                    "adhar_no"=>$adhar_no,
                                    "address"=>$result_patient['address'],
                                    "address_second"=>$result_patient['address2'],
                                    "address_third"=>$result_patient['address3'],
                                    "attended_doctor"=>$result['attend_doctor_id'],
                                    //"assigned_docotor_list"=>$result['age_m'],
                                    "authorization_amount"=>$result['authrization_amount'],
                                    "id_number"=>$result['panel_id_no'],
                                    "room_id"=>$result['room_type_id'],
                                    "room_no_id"=>$result['room_id'],
                                    //"time_unit"=>$result_patient['age_m'],
                                    "bed_no_id"=>$result['bad_id'],
                                    "patient_type"=>$result['patient_type'],
                                    "package_id"=>$result['package_id'],
                                    "package"=>$result['package_type'],
                                    "remarks"=>$result['remarks'],
                                    "advance_deposite"=>$result['advance_payment'],
                                    "panel_type"=>$result['panel_type'],
                                    "policy_number"=>$result['panel_polocy_no'],
                                    "company_name"=>$result['panel_name'],
                                    "admission_time"=>$result['admission_time'],
                                    "admission_date"=>date('d-m-Y',strtotime($result['admission_date'])),
                                   'payment_mode'=>$result['payment_mode'],
                                    'field_name'=>$total_values,
                                    "country_code"=>"+91",
                                    'mlc'=>$result['mlc'],
                                    'referred_by'=>$result['referred_by'],
                                    'referral_hospital'=>$result['referral_hospital'],
                                    'discharge_date'=>$discharge_date,
                                    
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              $ipd_booking_id =  $this->ipd_booking->save();
              $this->session->set_userdata('ipd_booking_id',$ipd_booking_id);
              $this->session->set_flashdata('success','IPD booking has been successfully updated.');
              redirect(base_url('ipd_booking/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();
                //print_r($data['form_error']); exit;
            }     
        }
        $data['btn_name'] = 'Save';
        $this->load->view('ipd_booking/add',$data);  

      }
    }

  public function print_ipd_booking_recipt($id="")
  {
      $data['page_title'] = "Print Bookings";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      elseif(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
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
      //print_r($template_format); exit;
      //Package
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('ipd_booking_receipt',$branch_id);
      $data['date_time_status'] = $get_date_time_setting->date_time_status;

      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      //$data['medicine_ids']=$medicine_arr;
      $data['template_data']=$template_format;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
      $this->load->view('ipd_booking/print_ipd_reciept_template',$data);
    }

  public function print_ipd_adminssion_card($id="")
  {
      $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
      $data['page_title'] = "Print Bookings";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
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

      $template_format = $this->ipd_admission_print->template_format(array('setting_name'=>'IPD_PRINT_SETTING','unique_id'=>1,'type'=>0),$branch_id);

      
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      $data['template_data']=$template_format->setting_value;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';
      $this->load->model('general/general_model');
      $get_date_time_setting = $this->general_model->get_date_time_setting('ipd_admission_print');
      $data['date_time_status'] = $get_date_time_setting->date_time_status;

      $this->load->view('ipd_booking/print_ipd_admission_template',$data);
    }

    


    private function _validate()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post();
        $field_list = mandatory_section_field_list(6);   
        //print_r($post);die; 
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');

        if(!empty($field_list)){ 
            if($field_list[0]['mandatory_field_id']=='30' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }
           
            if($field_list[1]['mandatory_field_id']=='31' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ 
                $this->form_validation->set_rules('age_y', 'age', 'trim|required|numeric|max_length[3]'); 
            }

            if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id'])
            { 
                if(in_array('38',$users_data['permission']['section']) && $post['referred_by']=='0')
                 {
                    $this->form_validation->set_rules('referral_doctor', 'referred by doctor', 'trim|required');
                 
                 }
                 else if(in_array('174',$users_data['permission']['section']) && $post['referred_by']=='1')
                 {
                    $this->form_validation->set_rules('referral_hospital', 'referred by hospital', 'trim|required');
                 }
            }
        }

        $this->form_validation->set_rules('name', 'patient name', 'trim|required');
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required');
        //$this->form_validation->set_rules('mobile', 'mobile', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        //$this->form_validation->set_rules('age_y', 'year', 'trim|required');
        if(isset($post['package_id'])){
          $this->form_validation->set_rules('package_id', 'package name', 'trim|required'); 
        }
        $this->form_validation->set_rules('adhar_no', 'adhar no', 'min_length[12]|max_length[16]');
        $this->form_validation->set_rules('room_id', 'room type', 'trim|required');
        $this->form_validation->set_rules('room_no_id', 'room no.', 'trim|required');
         $this->form_validation->set_rules('bed_no_id', 'bed no.', 'trim|required');
         //if($post['referred_by']=='0')
         //{
         
        
        $this->form_validation->set_rules('attended_doctor', 'attended doctor', 'trim|required');
        $this->form_validation->set_rules('assigned_doctor_list[]', 'assigned docotor list', 'trim|required');
        //$this->form_validation->set_rules('advance_deposite', 'advance deposite', 'trim|required');
        $this->form_validation->set_rules('admission_date', 'admission date', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        if(isset($post['field_name']))
        {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }

       
        $total_values=array();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

            for($i=0;$i<$count_field_names;$i++) {
            $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            } 
        }
      
        
        /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
         $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
         $this->form_validation->set_rules('card_no', 'Card no', 'trim|required');
         }
         if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
          $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
          $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
        
         }
       if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
        $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
         $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
        }*/
           
       // $this->form_validation->set_rules('mobile', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
       
        if ($this->form_validation->run() == FALSE) 
        {  
              $patient_code = generate_unique_id(4); 

              

              if(isset($post['panel_type'])){
                $panel_type=$post['panel_type'];

              }else{
                $panel_type='';
              }
              if(isset($post['policy_number'])){
                $policy_number=$post['policy_number'];
              }else{
                $policy_number='';
              }
              if(isset($post['company_name'])){
                $company_name=$post['company_name'];
              }else{
                 $company_name='';
              }
              if(isset($post['authorization_amount'])){
                $authorization_amount=$post['authorization_amount'];
              }else{
                $authorization_amount='';
              }
              if(isset($post['package_id'])){
                $package_id=$post['package_id'];
              }else{
                $package_id='';
              }
              if(isset($post['id_number'])){
                $id_number=$post['id_number'];
              }else{
                $id_number='';
              }  
               $data['form_data'] = array(
                                   "patient_id"=>$post['patient_id'],
                                    "data_id"=>$post['data_id'],
                                    "patient_reg_code"=>$patient_code,
                                    "name"=>$post['name'],
                                    'ipd_no'=>$post['ipd_no'],
                                    'simulation_id'=>$post['simulation_id'],
                                    "mobile"=>$post['mobile'],
                                    "age_m"=>$post['age_m'],
                                    "age_d"=>$post['age_d'],
                                    "gender"=>$post['gender'],
                                    "age_y"=>$post['age_y'],
                                    "adhar_no"=>$post['adhar_no'],
                                    "address"=>$post['address'],
                                    "address_second"=>$post['address_second'],
                                    "address_third"=>$post['address_third'],
                                    "attended_doctor"=>$post['attended_doctor'],
                                    'referral_doctor'=>$post['referral_doctor'],
                                    "authorization_amount"=>$authorization_amount,
                                    "id_number"=>$id_number,
                                    "room_id"=>$post['room_id'],
                                    "room_no_id"=>$post['room_no_id'],
                                    'remarks'=>$post['remarks'],
                                    //"time_unit"=>$post['time_unit'],
                                    "bed_no_id"=>$post['bed_no_id'],
                                    "patient_type"=>$post['patient_type'],
                                    "package_id"=>$package_id,
                                    "package"=>$post['package'],
                                    "advance_deposite"=>$post['advance_deposite'],
                                    "panel_type"=>$panel_type,
                                    "policy_number"=>$policy_number,
                                    "company_name"=>$company_name,
                                    "admission_time"=>$post['admission_time'],
                                    "admission_date"=>date('d-m-Y'),
                                    'payment_mode'=>$post['payment_mode'],
                                    'field_name'=>$total_values,
                                    "country_code"=>"+91",
                                    "mlc"=>$post['mlc'],
                                    'referred_by'=>$post['referred_by'],
                                    'referral_hospital'=>$post['referral_hospital'],
                                    'discharge_date'=>$post['discharge_date'],
                                   );  
              
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission(121,736);
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_booking->delete($id);
           $response = "IPD Booking successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission(121,736);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_booking->deleteall($post['row_id']);
            $response = "IPD Booking successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
         unauthorise_permission(121,733);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_booking->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
        $this->load->view('ipd_booking/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(121,737);
        $data['page_title'] = 'IPD Booking archive list';
        $this->load->helper('url');
        $this->load->view('ipd_booking/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission(121,737);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive'); 

        $list = $this->ipd_booking_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_booking) { 
            $no++;
            $row = array();
          
            
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
           
          $row[] = '<input type="checkbox" name="ipd_booking[]" class="checklist" value="'.$ipd_booking->id.'">';  
            $row[] = $ipd_booking->ipd_no;
            $row[] = $ipd_booking->patient_code;
            $row[] = $ipd_booking->patient_name;
            //$row[] = $purchase->total_amount;
            $row[] = $ipd_booking->mobile_no;
            $row[] = date('d-m-Y',strtotime($ipd_booking->admission_date)); //$ipd_booking->admission_date;
            $row[] = $ipd_booking->doctor_name;
            $row[] = $ipd_booking->room_no;
            $row[] = $ipd_booking->bad_no;
            $row[] = $ipd_booking->address;
            $row[] = $ipd_booking->remarks;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_booking->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('739',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_booking('.$ipd_booking->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('738',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$ipd_booking->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_booking_archive->count_all(),
                        "recordsFiltered" => $this->ipd_booking_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(121,739);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_booking_archive->restore($id);
           $response = "IPD Booking successfully restore in IPD list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(121,739);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_booking_archive->restoreall($post['row_id']);
            $response = "IPD Booking successfully restore in IPD list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(121,738);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_booking_archive->trash($id);
           $response = "IPD Booking successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(121,738);
        $this->load->model('ipd_booking/ipd_booking_archive_model','ipd_booking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_booking_archive->trashall($post['row_id']);
            $response = "IPD Booking successfully deleted parmanently.";
            echo $response;
        }
    }
   
  public function select_room_number()
  {
         $this->load->model('general/general_model');
         $room_id= $this->input->post('room_id');
         $room_no_id= $this->input->post('room_no_id');
         if(!empty($room_id)){
           $data['number_rooms']= $this->general_model->room_no_list($room_id);
         }
        $dropdown = '<option value="">-Select-</option>'; 
        if(!empty($data['number_rooms']))
          {
             $selected='';
          foreach($data['number_rooms'] as $number_rooms)
          {
            if($room_no_id==$number_rooms->id){
              $selected='selected=selected';

            }else{
              $selected='';
            }
          $dropdown .= '<option value="'.$number_rooms->id.'"  '.$selected.'>'.$number_rooms->room_no.'</option>';
          }
        } 
        echo $dropdown; 
         
  }

  public function select_bed_no_number()
  {
        $this->load->model('general/general_model');
        $room_id= $this->input->post('room_id');
        $room_no_id= $this->input->post('room_no_id');
        $bed_id= $this->input->post('bed_id');
        $ipd_id= $this->input->post('ipd_id');
          if(!empty($room_id) && !empty($room_no_id))
          {
            $data['number_bed']= $this->general_model->number_bed_list($room_id,$room_no_id,$ipd_id);
          }
          //print_r($data['number_bed']); exit;
          $selected='';
          // print_r($data['number_bed']);die;
          $dropdown = '<option value="">-Select-</option>'; 
          if(!empty($data['number_bed']))
          {
            foreach($data['number_bed'] as $number_bed)
            {
              //print_r($number_bed);
              //echo $number_bed->ipd_is_deleted; 
              
              if($bed_id == $number_bed->id)
              {
                $selected="selected=selected";
              }
              else
              {
                $selected='';
              }
              if($bed_id == $number_bed->id && $number_bed->status==1)
              {

                $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_no.'</option>';
              }
              else
              {
                if($number_bed->ipd_is_deleted=='2' ||  ($number_bed->ipd_is_deleted=='' || ($number_bed->ipd_is_deleted!=1 && $number_bed->ipd_is_deleted!=0) ))
                  { 
                    if($number_bed->status==1 && $number_bed->ipd_is_deleted==2)
                    {
                        $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_no.'</option>';  
                    }
                    else
                    {
                        $dropdown .= '<option value="'.$number_bed->id.'" '.$selected.'>'.$number_bed->bad_no.'</option>'; 
                    }  
                    
                  }
              }
             
            }
          } 
          echo $dropdown; 
  }

  public function update_discharge_data($ipd_id="",$patient_id)
  {
        $result= $this->ipd_booking->update_discharge_data($ipd_id,$patient_id);  
        if(!empty($result)){
          $this->session->set_flashdata('success','Patient has been discharge successfully.');
          redirect(base_url('ipd_discharge_bill/discharge_bill_info/'.$ipd_id.'/'.$patient_id));
        }
  }

  public function readmit($ipd_id="",$patient_id)
  {
      $result= $this->ipd_booking->re_admit_patient($ipd_id,$patient_id);  
      if(!empty($result))
      {
          $this->load->model('general/general_model');
          $data['page_title']="Re-admit";
          $data['patient_details']= $this->general_model->get_patient_according_to_ipd($ipd_id,$patient_id);
          $data['room_category']= $this->general_model->check_room_type();
          $post= $this->input->post();
          $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'ipd_id'=>$ipd_id,
                                  "patient_id"=>$patient_id,
                                  "room_id"=>'',
                                  "room_no_id"=>'',
                                  'bed_no_id'=>'',
                                  //'card_no'=>'',
                                  'transfer_date'=>date('d-m-Y'),
                                  "transfer_time"=>date('H:i:s')
                                  );    

        if(isset($post) && !empty($post))
        { 
            $data['form_data'] = $this->_validate_readmit();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_booking->save_readmit();
                //$this->session->set_userdata('ipd_room_transfer_id',$ipd_room_transfer_id);
                $this->session->set_flashdata('success','Patient re-admited successfully.');
               
                redirect(base_url('ipd_booking/')); // /?status=print
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }  
         }
       //print_r( $data['form_error']);die;
       $this->load->view('ipd_booking/re_admit',$data); 
        //$this->session->set_flashdata('success','Patient has been re-admited successfully.');
        //redirect(base_url('ipd_booking/'));
      }
  }


  private function _validate_readmit()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('room_id', 'room type', 'trim|required'); 
        $this->form_validation->set_rules('room_no_id', 'room no', 'trim|required'); 
        $this->form_validation->set_rules('bed_no_id', 'bed no', 'trim|required'); 
        $this->form_validation->set_rules('transfer_date', 're-admit date', 'trim|required'); 
        $this->form_validation->set_rules('transfer_time', 're-admit time', 'trim|required'); 
      
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                      'data_id'=>"", 
                                      'ipd_id'=>$post['ipd_id'],
                                      "patient_id"=>$post['patient_id'],
                                      "room_id"=>$post['room_id'],
                                      "room_no_id"=>$post['room_no_id'],
                                      'bed_no_id'=>$post['bed_no_id'],
                                      //'card_no'=>'',
                                      'transfer_date'=>date('d-m-Y',strtotime($post['transfer_date'])),
                                      "transfer_time"=>date('H:i:s',strtotime($post['transfer_time']))
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

    function mlc_print()
    {
     
      /*$data['page_title'] = "Medic-legal Certification";
      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
      $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      
      $users_data = $this->session->userdata('auth_users'); 
      


      
      $data['payment_mode'] = $get_by_id_data['ipd_list'][0]->payment_mode;
      $data['template_data']=$template_format->setting_value;
      //print_r($data['template_data']);
      $data['all_detail']= $get_by_id_data;
      $data['page_type'] = 'Booking';*/

      $ipd_booking_id= $this->session->userdata('ipd_booking_id');
      
      if(!empty($id))
      {
        $ipd_booking_id = $id;
      }
      else if(isset($ipd_booking_id) && !empty($ipd_booking_id))
      {
        $ipd_booking_id =$ipd_booking_id;
      }
      else
      {
        $ipd_booking_id = '';
      } 
      $get_by_id_data = $this->ipd_booking->get_all_detail_print($ipd_booking_id);
      //print_r($get_by_id_data);die;

      $data['get_by_id_data']=$get_by_id_data;
      //print '<pre>';print_r($data['get_by_id_data']['ipd_list'][0]);die;
      $this->load->view('ipd_booking/mlc_form_ipd',$data);
    }


}
