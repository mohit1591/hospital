<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_summary_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('summary_reports/ipd_summary_report_model','ipd_collection_report');
        $this->load->library('form_validation');

    }

    public function index()
    {   
        
        $this->session->unset_userdata('ipd_collection_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('ipd_collection_search_data');
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
        $data['page_title'] = 'IPD Summary Report list';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->view('ipd_summary/list',$data);

    }
    
    public function ajax_list()
    {  
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->ipd_collection_report->get_datatables();
        //echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        foreach ($list as $reports) 
        {

               
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


                                 
            $no++;

            $IPD_refund_amount='0';
            if($reports->ipd_refund_amount>0)
            {
              $IPD_refund_amount = $reports->ipd_refund_amount;
            }
            /*$discount_amt=0;
            if($reports->discount_amount_dis_bill>0)
            {
              $discount_amt = $reports->discount_amount_dis_bill;
            }*/
            
            
            
            $paid_amountse = $reports->ipd_paid_amount-$IPD_refund_amount;
            /*if(!empty($discount_amt))
            {
               $paid_amountse = $paid_amountse-$discount_amt;
            }*/
            if(!empty($paid_amountse))
            {
              $paid_amounts = number_format($paid_amountse,2);
            }
            //$grand_total_amount = $grand_total_amount+$paid_amountse;
            
            $grand_total_amount = $grand_total_amount+$reports->net_amount_dis_bill;
            
            
            $row = array(); 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script; 
            $row[] = $i;  
            //$row[] = date('d-m-Y h:i A',strtotime($reports->discharge_date));
            $row[] = $reports->discharge_bill_no;  
            $row[] = $reports->ipd_no;
            $row[] = $reports->patient_name;
            $row[] = $reports->patient_code;
            $row[] = date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i A',strtotime($reports->admission_time));
            $row[] = date('d-m-Y h:i A',strtotime($reports->discharge_date));
            //$row[] = $reports->particular_name;
            $row[] = $reports->net_amount_dis_bill;
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
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($grand_total_amount,2).' readonly >';  
              
              $tot_row[]='';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }
           $i++;
        }
        
        $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->ipd_collection_report->count_all(),
                      "recordsFiltered" => $this->ipd_collection_report->count_filtered(),
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
         $this->session->set_userdata('ipd_summary_report',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('ipd_summary_report');
    }

    public function ipd_summary_report_excel()
    {
        
         
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          // Field names in the first row
          $fields = array('S. No.','Receipt No.','IPD No.','Patient Name','Reg. No.', 'DOA','DOD','Amount');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
            
            $ttl_paid=0;
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
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          
          $list = $this->ipd_collection_report->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $m=1;
               foreach($list as $reports)
               {
                  
                    $IPD_refund_amount='0';
                    if($reports->ipd_refund_amount>0)
                    {
                      $IPD_refund_amount = $reports->ipd_refund_amount;
                    }
                    $paid_amountse = $reports->ipd_paid_amount-$IPD_refund_amount;
                    if(!empty($paid_amountse))
                    {
                      $paid_amounts = number_format($paid_amountse,2);
                    }
                    //$ttl_paid = $ttl_paid+ $paid_amountse;

                    $ttl_paid = $ttl_paid+$reports->net_amount_dis_bill;
                    
                    array_push($rowData,$m,$reports->discharge_bill_no,$reports->ipd_no,$reports->patient_name,$reports->patient_code,date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i A',strtotime($reports->admission_time)),date('d-m-Y h:i A',strtotime($reports->discharge_date)),$reports->net_amount_dis_bill);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                      $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;  
                $m++;
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
                $objPHPExcel->getActiveSheet()->getStyle('F'.$row.':H'.$row.'')->getFont()->setBold( true );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,'Total');
                if(!empty($ttl_paid))
                {
                  $ttl_paid = number_format($ttl_paid,2);
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_paid);
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=ipd_summary_report_".time().".xls");   
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
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('ipd_summary_report',$post); 
        }
        $data['ipd_summary_report'] = $this->session->userdata('ipd_summary_report');
        if(isset($data['ipd_summary_report']) && !empty($data['ipd_summary_report']))
        {
           $ipd_collection_report = $data['ipd_summary_report'];
           if(isset($ipd_collection_report['start_date']) && !empty($ipd_collection_report['start_date']))
           {
            $start_date=$ipd_collection_report['start_date'];
           }
           else
           {
            $start_date='';
           }
            
            if(isset($ipd_collection_report['end_date']) && !empty($ipd_collection_report['end_date']))
           {
            $end_date=$ipd_collection_report['end_date'];
           }
           else
           {
            $end_date='';
           }
            
           $data['form_data'] = array(
                                   'start_date'=>$start_date,
                                   'end_date'=> $end_date,
                                   );
        }
        else
        {
            $data['form_data'] = array(
                                   'start_date'=>date('d-m-Y'),
                                   
                                   'end_date'=>date('d-m-Y'),
                                   );
        }  
        $this->load->view('ipd_summary/advance_search',$data);
    }

    public function pdf_ipd_summary_report()
    {  
        unauthorise_permission('88','568');  
        $data['print_status']="";
        $data['data_list'] = $this->ipd_collection_report->search_report_data();
        $this->load->view('ipd_summary/ipd_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ipd_summary_report_report_".time().".pdf");
    }

    public function print_ipd_collection_report()
    {    
        $data['print_status']="1";
        $data['data_list'] = $this->ipd_collection_report->search_report_data();
        $this->load->view('ipd_summary/ipd_report_html',$data); 
    }
    public function get_allsub_branch_list()
    {
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
    public function print_ipd_collection_reports()
    { 
      unauthorise_permission('88','569');
     $get = $this->input->get();
     $data['billing_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['billing_collection_list'] = $this->ipd_collection_report->get_billing_collection_details($get);
     } 
     $this->load->view('ipd_summary/list_ipd_collection_report',$data);  

    }

    
     
}
?>