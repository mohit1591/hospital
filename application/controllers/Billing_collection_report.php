<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_collection_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('reports/billing_collection_report_model','billing_collection_report');
        $this->load->library('form_validation');

    }

    public function index()
    {   
        unauthorise_permission('88','570');
        $this->session->unset_userdata('billing_collection_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('billing_collection_search_data');
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
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Billing Collection Report';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->view('billing_collection/list',$data);
    }
    public function expenses(){
       $data['page_title'] = 'OPD Billing Collection Report';
       $this->load->view('billing_collection/billing_collection_report',$data);
    }
    public function ajax_list()
    {  
         unauthorise_permission('88','570');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->billing_collection_report->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        $grand_total_discount=0;
        //$grand_net_amount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0;
        foreach ($list as $reports) 
        {

               $reports->total_amount;
               ////////// Check  List /////////////////
               $grand_total_amount = $grand_total_amount+$reports->total_amount;
               $grand_total_discount = $grand_total_discount + $reports->discount;
               //$grand_net_amount = $grand_net_amount + $reports->net_amount;
               $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
               $grand_balance_amount = $grand_balance_amount + $reports->balance;


               if($i==$total_num){
                    $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
               } 
                if($reports->status==1)
                {
                    $status = '<font color="green">Active</font>';
                }   
                else{
                    $status = '<font color="red">Inactive</font>';
                }
                if($reports->booking_status==0)
                {
                    $booking_status = '<font color="red">Pending</font>';
                }   
                elseif($reports->booking_status==1){
                    $booking_status = '<font color="green">Confirm</font>';
                }                 
                elseif($reports->booking_status==2){
                    $booking_status = '<font color="blue">Attended</font>';
                }                 
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script; 
            $row[] = $i; 
            $row[] = $reports->token_no; 
            $row[] = $reports->reciept_code;  
            $row[] = date('d-m-Y',strtotime($reports->booking_date));  
            $row[] = $reports->patient_name;
            $row[] = 'Dr. '.$reports->doctor_name;
            $row[] = $reports->doctor_hospital_name;    
            $row[] = $reports->total_amount;  
            $row[] = $reports->discount;  
            //$row[] = $reports->net_amount;  
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance; 
            //$row[] = $booking_status;  
                    
            
               if(in_array('565',$users_data['permission']['action']))
               {
                    $btn_edit = ' <a class="btn-custom" href="'.base_url("opd_billing/edit/".$reports->id).'" title="Edit Billing"><i class="fa fa-pencil"></i> Edit</a>';
               }

             if($reports->blnce>0 && $reports->parent_id>0){
           $print_url = "'".base_url('opd_billing/print_billing_report/'.$reports->id)."'";
          $btn_print = ' <a class="btn-custom" href="javascript:void(0)" onclick = "return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print  </a>';   
            
            }else{
            $print_url = "'".base_url('balance_clearance/print_patient_balance_receipt/'.$reports->pay_id.'/'.$reports->patient_new_id.'/'.$reports->c_date.'/'.$reports->section_id)."'";
            $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
            }

            //$btn_edit = ' <a class="btn-custom" href="'.base_url("opd/edit_booking/".$reports->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            //$btn_delete = ' <a class="btn-custom" onClick="return delete_test('.$reports->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';  
           $row[] = $btn_print;
           $row[] = $btn_edit;
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
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;"  value='.number_format($grand_total_discount,2).' readonly >';   
              //$tot_row[] = '<input type="text" class="input-tiny" value='.$grand_net_amount.' readonly >';   
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_paid_amount,2).' readonly >'; 
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_balance_amount,2).' readonly >'; 
              
              $tot_row[] = '';
              $data[] = $tot_row; 
           }
           $i++;
        }
        
        $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->billing_collection_report->count_all(),
                      "recordsFiltered" => $this->billing_collection_report->count_filtered(),
                      "data" => $data,
              );
      
        //output to json format
        echo json_encode($output);
    }

    public function search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
	     $search_data =  array(
                                   'start_date'=>$post['start_date'],
                                   'branch_id'=>$post['branch_id'],
                                   'referral_doctor'=>'',
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>$post['end_date'],
                                   'attended_doctor'=>'',
                                   'employee'=>$post['employee'],
                                 );
         $this->session->set_userdata('billing_collection_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('billing_collection_search_data');
    }

    public function billing_collection_report_excel()
    {
        
         unauthorise_permission('88','566');
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          // Field names in the first row
          $fields = array('S. NO.','Token No.','Receipt No.','Billing Date','Patient Name', 'Consultant','Referred By','Total Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          // added on 02-feb-2018  
            $ttl_amt=0;
            $ttl_disc=0;
            $ttl_paid=0;
            $ttl_bal=0;
          // added on 02-feb-2018
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          $this->load->model('general/general_model');
          $get_date_time_setting = $this->general_model->get_date_time_setting('medicine_collection_report');
          $date_time_status = $get_date_time_setting->date_time_status;
          $list = $this->billing_collection_report->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $reports)
               {
                  // added on 02-Feb-2018
                      $ttl_amt = $ttl_amt  + $reports->total_amount;
                      $ttl_disc= $ttl_disc + $reports->discount;
                      $ttl_paid= $ttl_paid + $reports->paid_amount;
                      $ttl_bal=  $ttl_bal  + $reports->balance;
                  // added on 02-Feb-2018

                    if($date_time_status==1)
                    {
                      $bill_date = date('d-m-Y',strtotime($reports->booking_date)).' '.date('h:i A',strtotime($reports->c_date));
                    } 
                    else
                    {
                      $bill_date = date('d-m-Y',strtotime($reports->booking_date));
                    }
                    array_push($rowData,$k,$reports->token_no,$reports->reciept_code,$bill_date ,$reports->patient_name,'Dr. '.$reports->doctor_name,$reports->doctor_hospital_name,$reports->total_amount,$reports->discount,$reports->paid_amount,$reports->balance);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                      $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;  
                    $k++;
               }
             
          }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $reports_data)
               {
                    $col = 0;
                     $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                         
                    }
                    $row++;
               }

              // added on 02-Feb-2018 
                $objPHPExcel->getActiveSheet()->getStyle('G'.$row.':K'.$row.'')->getFont()->setBold( true );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_amt);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,$ttl_disc);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,$ttl_paid);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row,$ttl_bal);
              // added on 02-Feb-2018
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=billing_collection_report_".time().".xls");   
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }
      
    }

    public function billing_collection_report_csv()
    {
         unauthorise_permission('88','567');
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          // Field names in the first row
          $fields = array('Receipt No.','Billing Date','Patient Name','Consultant','Referred By','Total Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;

          // added on 02-feb-2018  
            $ttl_amt=0;
            $ttl_disc=0;
            $ttl_paid=0;
            $ttl_bal=0;
          // added on 02-feb-2018

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
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $this->load->model('general/general_model');
          $get_date_time_setting = $this->general_model->get_date_time_setting('medicine_collection_report');
          $date_time_status = $get_date_time_setting->date_time_status;
          $list = $this->billing_collection_report->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                  // added on 02-Feb-2018
                      $ttl_amt = $ttl_amt  + $reports->total_amount;
                      $ttl_disc= $ttl_disc + $reports->discount;
                      $ttl_paid= $ttl_paid + $reports->paid_amount;
                      $ttl_bal=  $ttl_bal  + $reports->balance;
                  // added on 02-Feb-2018
                    if($date_time_status==1)
                    {
                      $bill_date = date('d-m-Y',strtotime($reports->booking_date)).' '.date('h:i A',strtotime($reports->c_date));
                    } 
                    else
                    {
                      $bill_date = date('d-m-Y',strtotime($reports->booking_date));
                    }
                    array_push($rowData,$reports->reciept_code,$bill_date,$reports->patient_name,'Dr. '.$reports->doctor_name,$reports->doctor_hospital_name,$reports->total_amount,$reports->discount,$reports->paid_amount,$reports->balance);
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
               foreach($data as $reports_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }

              // added on 02-Feb-2018 
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_amt);
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$ttl_disc);
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,$ttl_paid);
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_bal);
              // added on 02-Feb-2018
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=billing_collection_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }
       
    }

    public function advance_search()
    {
        $data['page_title'] = "Advance Search";
        $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('billing_collection_search_data',$post); 
        }
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->general_model->branch_user_list();   
        $data['insurance_type_list'] = $this->general_model->insurance_type_list();
        $data['insurance_company_list'] = $this->general_model->insurance_company_list();      
        //$data['employee_list'] = $this->opd->employee_list();

$data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();

        $data['profile_list'] = $this->opd->profile_list();
        $data['billing_collection_search_data'] = $this->session->userdata('billing_collection_search_data');
        if(isset($data['billing_collection_search_data']) && !empty($data['billing_collection_search_data']))
        {
           $billing_collection_search_data = $data['billing_collection_search_data'];
           if(isset($billing_collection_search_data['start_date']) && !empty($billing_collection_search_data['start_date']))
           {
            $start_date=$billing_collection_search_data['start_date'];
           }
           else
           {
            $start_date='';
           }
            if(isset($billing_collection_search_data['patient_code']) && !empty($billing_collection_search_data['patient_code']))
           {
            $patient_code=$billing_collection_search_data['patient_code'];
           }
           else
           {
            $patient_code='';
           }
            if(isset($billing_collection_search_data['patient_name']) && !empty($billing_collection_search_data['patient_name']))
           {
            $patient_name=$billing_collection_search_data['patient_name'];
           }
           else
           {
            $patient_name='';
           }
            if(isset($billing_collection_search_data['mobile_no']) && !empty($billing_collection_search_data['mobile_no']))
           {
            $mobile_no=$billing_collection_search_data['mobile_no'];
           }
           else
           {
            $mobile_no='';
           }
            if(isset($billing_collection_search_data['end_date']) && !empty($billing_collection_search_data['end_date']))
           {
            $end_date=$billing_collection_search_data['end_date'];
           }
           else
           {
            $end_date='';
           }
            if(isset($billing_collection_search_data['attended_doctor']) && !empty($billing_collection_search_data['attended_doctor']))
           {
            $attended_doctor=$billing_collection_search_data['attended_doctor'];
           }
           else
           {
            $attended_doctor='';
           }
            if(isset($billing_collection_search_data['particular']) && !empty($billing_collection_search_data['particular']))
           {
            $particular=$billing_collection_search_data['particular'];
           }
           else
           {
             $particular='';
           }
            if(isset($billing_collection_search_data['employee']) && !empty($billing_collection_search_data['employee']))
           {
            $employee=$billing_collection_search_data['employee'];
           }
           else
           {
             $employee='';
           }

if(isset($billing_collection_search_data['referred_by']) && !empty($billing_collection_search_data['referred_by']))
           {
              $referred_by = $billing_collection_search_data['referred_by'];
           }
           else
           {
             $referred_by='';
           }
            if(isset($billing_collection_search_data['referral_hospital']) && !empty($billing_collection_search_data['referral_hospital']))
           {
              $referral_hospital = $billing_collection_search_data['referral_hospital'];
           }
           else
           {
             $referral_hospital='';
           }
            if(isset($billing_collection_search_data['refered_id']) && !empty($billing_collection_search_data['refered_id']))
           {
              $refered_id = $billing_collection_search_data['refered_id'];
           }
           else
           {
            $refered_id='';
           }
           
            if(isset($billing_collection_search_data['particulars']) && !empty($billing_collection_search_data['particulars']))
           {
            $particulars=$billing_collection_search_data['particulars'];
           }
           
           else
           {
            $particulars='';
          }
           $data['form_data'] = array(
                                   'start_date'=>$start_date,
                                   'patient_code'=>$patient_code,
                                   'patient_name'=>$patient_name,
                                   'mobile_no'=>$mobile_no,
                                   'end_date'=> $end_date,
                                   'attended_doctor'=>$attended_doctor,
                                   'particular'=>$particular,
                                   'employee'=>$employee,
                                   'particulars'=>$particulars,
'referred_by'=>$referred_by,
                                   'referral_hospital'=>$referral_hospital,
                                   'refered_id'=>$refered_id,
                                   "insurance_type"=>$billing_collection_search_data['insurance_type'],
                                    "insurance_type_id"=>$billing_collection_search_data['insurance_type_id'],
                                    "ins_company_id"=>$billing_collection_search_data['ins_company_id'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'start_date'=>date('d-m-Y'),
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>date('d-m-Y'),
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'particular'=>'',
                                   'employee'=>'',
                                   'particulars'=>'',
                                   "referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                    "insurance_type"=>"0",
                                    "insurance_type_id"=>"",
                                    "ins_company_id"=>"",
                                   
                                 );
        }  
        $this->load->view('billing_collection/advance_search',$data);
    }

    public function pdf_billing_collection_report()
    {  
        unauthorise_permission('88','568');  
        $data['print_status']="";
        $data['data_list'] = $this->billing_collection_report->search_report_data();
        $this->load->view('billing_collection/billing_collection_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("billing_collection_report_report_".time().".pdf");
    }

    public function print_billing_collection_report()
    {    unauthorise_permission('88','569');
        $data['print_status']="1";
        $data['data_list'] = $this->billing_collection_report->search_report_data();
        $this->load->view('billing_collection/billing_collection_report_html',$data); 
    }
     public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
               if(!empty($sub_branch_details)){
                    $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" ><option value="">Select Sub Branch</option><option value="all" >All</option></option><option  selected="selected"  value='.$users_data['parent_id'].'>Self</option>';
                 
                     $i=0;
                     foreach($sub_branch_details as $key=>$value){
                         $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                         $i = $i+1;
                    }
               }
               $dropdown.='</select>';
               echo $dropdown; 
        }
         
       
    }
    public function print_billing_collection_reports()
    { 
      unauthorise_permission('88','569');
     $get = $this->input->get();
     $data['billing_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['billing_collection_list'] = $this->billing_collection_report->get_billing_collection_details($get);
     } 
     $this->load->view('billing_collection/list_billing_collection_report',$data);  

    }

    
     
}
?>