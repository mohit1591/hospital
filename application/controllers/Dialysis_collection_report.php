<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_collection_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('reports/dialysis_collection_report_model','dialysis_collection_reports');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        //unauthorise_permission('126','772');
        $this->session->unset_userdata('dialysis_collection_resport_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('dialysis_collection_resport_search_data');
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
        $data['page_title'] = 'Dialysis Collection Report';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->view('dialysis_collection/list',$data);
    }
    public function expenses(){
       $data['page_title'] = 'Dialysis Collection Report';
       $this->load->view('dialysis_collection/expense_report',$data);
    }
    public function ajax_list()
    {  
        //unauthorise_permission('126','772');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->dialysis_collection_reports->get_datatables();
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
         // print_r($reports);die;
            ////////// Check  List /////////////////
              //$grand_total_amount = $grand_total_amount+$reports->total_amount;
              //$grand_total_discount = $grand_total_discount + $reports->discount;
              //$grand_net_amount = $grand_net_amount + $reports->net_amount;
              $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
              //$grand_balance_amount = $grand_balance_amount + $reports->balance;
               $check_script = "";
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
                
                               
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            $row[] = $reports->booking_code;  
            $row[] = date('d-m-Y',strtotime($reports->dialysis_date));  
            $row[] = $reports->patient_name;  
            $row[] = $reports->doctor_hospital_name;  
            $row[] = $reports->paid_amount;
$row[] = date('d-m-Y',strtotime($reports->c_date));  
            
            $btn_edit = ' <a class="btn-custom" href="'.base_url("dialysis_booking/edit/".$reports->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
            
            $adva_id = '';
            if($reports->type==4)
            {
                
                if($reports->paid_amount!='0.00')
                {
                    
                $p_date =    date('d-m-Y',strtotime($reports->c_date));
                  $adva_id = get_adv_id($p_date,$reports->paid_amount,$reports->id);
                  $print_pdf_url = "'".base_url('advance_payment/print_advance_payment_report/'.$adva_id)."'";
    
                }
                else
                {
                  $print_pdf_url = "'".base_url('dialysis_booking/print_dialysis_booking_recipt/'.$reports->id)."'";

                }

  $btn_print = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';

                
            }
            else if($reports->type==0)
            {
//balance clearence                
$print_url = "'".base_url('balance_clearance/print_patient_balance_receipt/'.$reports->pay_id.'/'.$reports->patient_new_id.'/'.$reports->c_date.'/'.$reports->section_id)."'";

                $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';

               
            }
            else
            {
                //discharge bill
                $print_pdf_url = "'".base_url('dialysis_booking/print_procedure_bill/'.$reports->id.'/'.$reports->patient_new_id)."'";
               

                $btn_print = ' <a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_pdf_url.')"  title="Print" ><i class="fa fa-print"></i> Print  </a>';

                
            }


            $row[]=$btn_edit.$btn_print;
            $data[] = $row;
            $tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_paid_amount,2,'.','').' readonly >'; 
              $tot_row[] = '';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_collection_reports->count_all(),
                        "recordsFiltered" => $this->dialysis_collection_reports->count_filtered(),
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
         $this->session->set_userdata('dialysis_collection_resport_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('dialysis_collection_resport_search_data');
    }

    public function dialysis_report_excel()
    {
        //unauthorise_permission('126','768');
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_collection_report');
        $date_time_status = $get_date_time_setting->date_time_status;
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Booking No.','Booking Date','Patient Name','Referred By','Paid Amount');
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
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $list = $this->dialysis_collection_reports->search_report_data();
          $rowData = array();
          $data= array();
          $ttl_paid=0;
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    $ttl_paid=$ttl_paid + $reports->paid_amount;  // added on 02-feb-2018
                    if($date_time_status==1)
                    {
                       $bookingdate = date('d-m-Y',strtotime($reports->dialysis_date)).' '.date('h:i A',strtotime($reports->dialysis_time));
                    } 
                    else
                    {
                      $bookingdate = date('d-m-Y',strtotime($reports->dialysis_date));
                    }
                    array_push($rowData,$reports->booking_code,$bookingdate ,$reports->patient_name,$reports->doctor_hospital_name,$reports->paid_amount);
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
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_paid);
              // added on 02-Feb-2018
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
        header("Content-Disposition: attachment; filename=dialysis_collection_report_".time().".xls"); 
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
                ob_end_clean();
               $objWriter->save('php://output');
         }
      
    }

    public function dialysis_report_csv()
    {
         // unauthorise_permission('126','769');
          // Starting the PHPExcel library
          $this->load->model('general/general_model');
          $get_date_time_setting = $this->general_model->get_date_time_setting('dialysis_collection_report');
          $date_time_status = $get_date_time_setting->date_time_status;
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Dialysis Code','Dialysis Date','Patient Name','Referred By','Paid Amount');
          
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;

            $ttl_paid=0; // added on 02-feb-2018

          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $list = $this->dialysis_collection_reports->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    $ttl_paid=$ttl_paid + $reports->paid_amount;  // added on 02-feb-2018
                    if($date_time_status==1)
                    {
                       $bookingdate = date('d-m-Y',strtotime($reports->dialysis_date)).' '.date('h:i A',strtotime($reports->dialysis_time));
                    } 
                    else
                    {
                      $bookingdate = date('d-m-Y',strtotime($reports->dialysis_date));
                    }
                    array_push($rowData,$reports->booking_code,$bookingdate,$reports->patient_name,$reports->doctor_hospital_name,$reports->paid_amount);
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
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFill()->applyFromArray(array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '37ac77' )  ));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_paid);
                // added on 02-Feb-2018
                $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=dialysis_collection_report_".time().".csv");
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
        $this->load->model('dialysis_booking/dialysis_booking_model','ipd_booking');
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();        
        $post = $this->input->post(); 
        
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('dialysis_collection_resport_search_data',$post); 
        }
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['search_data'] = $this->session->userdata('dialysis_collection_resport_search_data');
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $referred_by ='0';
           if(isset($search_data['referred_by']))
           {
              $referred_by = $search_data['referred_by'];
           }
           $referral_hospital='';
           if(isset($search_data['referral_hospital']))
           {
              $referral_hospital=$search_data['referral_hospital'];
           }
            $refered_id='';
           if(isset($search_data['refered_id']))
           {
              $refered_id=$search_data['refered_id'];
           }
           $data['form_data'] = array(
                                   'start_date'=>$search_data['start_date'],
                                    'end_date'=>$search_data['end_date'],
                                   'patient_code'=>$search_data['patient_code'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'attended_doctor'=>$search_data['attended_doctor'],
'refered_id'=>$refered_id,
                                   'referred_by'=>$referred_by,
                                   'referral_hospital'=>$referral_hospital,
                                   'branch_id'=>$search_data['branch_id'],
                                   'employee'=>$search_data['employee'],
                                   
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'start_date'=>'',
                                   'end_date'=>'',
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   
                                   "referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   'branch_id'=>'',
                                   'employee'=>'',
                                   );
        }  
        $this->load->view('dialysis_collection/advance_search',$data);
    }

    public function pdf_dialysis_report()
    {   
       // unauthorise_permission('126','770');
        $data['print_status']="";
        $data['data_list'] = $this->dialysis_collection_reports->search_report_data();
        $this->load->view('dialysis_collection/dialysis_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("dialysis_collection_report_".time().".pdf");
    }

    public function print_dialysis_report()
    {   
       // unauthorise_permission('126','771');
        $data['print_status']="1";
        $data['data_list'] = $this->dialysis_collection_reports->search_report_data();
        $this->load->view('dialysis_collection/dialysis_report_html',$data); 
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
    public function print_dialysis_collection_reports()
    { 
     //unauthorise_permission('126','771');
     $get = $this->input->get();
     $data['dialysis_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['dialysis_collection_list'] = $this->dialysis_collection_reports->get_dialysis_collection_list_details($get);
     } 
     $this->load->view('dialysis_collection/list_dialysis_collection',$data);  

    }

    public function print_procedure_receipt($dialysis_id="",$patient_id='',$pay_id='',$date='')
    {
        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Discharge Payment";
        if(!empty($dialysis_id))
        {
            $dialysis_id= $dialysis_id;
        }
        else
        {
          $dialysis_id= $this->session->userdata('dialysis_id');
        }
        
        $get_by_id_data = $this->dialysis_collection_reports->get_all_detail_print($pay_id,15);
        $get_balance_previous= $this->dialysis_collection_reports->get_balance_previous($pay_id,$patient_id,$date,15);
        // echo "<pre>"; print_r($get_by_id_data); exit;
        $template_format = $this->dialysis_collection_reports->template_format(array('section_id'=>15,'types'=>1));  
        $data['type']=5;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['user_detail']=$user_detail;
        $data['new_balance']=$get_balance_previous;
        $this->load->view('dialysis_collection/print_procedure_bill',$data);
 }

    
    
     
}
?>