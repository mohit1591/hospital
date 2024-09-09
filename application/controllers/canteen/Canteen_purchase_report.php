<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_purchase_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('canteen/reports/canteen_purchase_collection_report_model','canteen_collection_reports');
        $this->load->library('form_validation');
    }

    public function index()
    {  // unauthorise_permission('159','931');
        $this->session->unset_userdata('medicine_purchase_collection_resport_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('medicine_purchase_collection_resport_search_data');

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
        $data['form_data'] = array('from_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Purchase Report';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();
        $this->load->view('canteen/canteen_purchase_report/list',$data);
    }
    public function expenses(){
       $data['page_title'] = 'Canteen Collection Reports';
       $this->load->view('medicine_collection/expense_report',$data);
    }
    public function ajax_list()
    {  
       // unauthorise_permission('159','931');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->canteen_collection_reports->get_datatables();
       // echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        $grand_total_discount=0;
        //$grand_net_amount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0;
        foreach($list as $reports) 
        {
          //print_r($reports);
           
              $grand_total_discount = $grand_total_discount + $reports->discount;
              $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
              $grand_balance_amount = $grand_balance_amount + $reports->balance;
                
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            $row[] =  $reports->purchase_id; 
            $row[] = $reports->invoice_id;  
            $row[] = date('d-m-Y',strtotime($reports->purchase_date));
            $row[] = $reports->name;  
            
            $row[] = $reports->total_amount;
            $grand_total_amount = $grand_total_amount+$reports->total_amount;  
            $row[] = $reports->discount;  
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance; 
           
            if($reports->total_amount !='0.00' && $reports->parent_id>0){
               $print_url = "'".base_url('canteen/purchase/print_purchase_recipt/'.$reports->parent_id)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             }else{
               $print_url = "'".base_url('canteen/canteen_purchase_report/print_vendor_balance_receipt/'.$reports->pay_id.'/'.$reports->parent_id.'/'.$reports->paid_amount)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             }
           

         
              
            $row[] = $btn_print;        
            $data[] = $row;

            $tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;"  value='.number_format($grand_total_discount,2).' readonly >';   
              //$tot_row[] = '<input type="text" class="w-90px" value='.$grand_net_amount.' readonly >';   
              $tot_row[] = '<input type="text" class="w-50px" style="text-align:right;" value='.number_format($grand_paid_amount,2).' readonly >'; 
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_balance_amount,2).' readonly >'; 
              $tot_row[] = '';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->canteen_collection_reports->count_all(),
                        "recordsFiltered" => $this->canteen_collection_reports->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
       $search_data =  array(
                                   'from_date'=>$post['from_date'],
                                   'referral_doctor'=>'',
                                   'sale_no'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>$post['end_date'],
                                   'branch_id'=>$post['branch_id']
                                   
                                 );
         $this->session->set_userdata('medicine_purchase_collection_resport_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('medicine_purchase_collection_resport_search_data');
    }

    public function canteen_report_excel()
    {
         
       // unauthorise_permission('159','931');
              // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $grand_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;

          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
          $fields = array('Purchase No','Invoice No','Purchase Date','Vendor Name','Net Amount','Discount','Paid Amount','Balance');
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
      $this->load->model('general/general_model');
  
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {

                  $grand_total_amount = $grand_total_amount+$reports->total_amount;
                  $grand_total_discount = $grand_total_discount + $reports->discount;
                  $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
                  $grand_balance_amount = $grand_balance_amount + $reports->balance;
                  $discount=$reports->discount;
                  
        if(1)
        {
          $sale_date = date('d-m-Y',strtotime($reports->purchase_date));  
        } 
        else
        {
          $sale_date = date('d-m-Y',strtotime($reports->sale_date));
        }

                  array_push($rowData,$reports->purchase_id,$reports->invoice_id,$sale_date,$reports->name,number_format($reports->total_amount,2),$discount,number_format($reports->paid_amount,2),number_format($reports->balance,2));
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
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(3,$row+1,'Total');
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(4,$row+1,number_format($grand_total_amount,2));
                          $objWorksheet->setCellValueByColumnAndRow(5,$row+1,number_format($grand_total_discount,2));
                          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,number_format($grand_paid_amount,2));
                          $objWorksheet->setCellValueByColumnAndRow(7,$row+1,number_format($grand_balance_amount,2));
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
                // added on 31-jan-2018
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // added on 31-jan-2018
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=medicine_purchase_collection_report_".time().".xls");  
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          }
        
    
        
    }

     public function medicine_report_csv()
    {
       
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);

            $grand_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
          $fields = array('Purchase No','Invoice No','Purchase Date','Vendor Name','Net Amount','Discount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading=1;
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
          $this->load->model('general/general_model');
         
          $list =  $this->canteen_collection_reports->search_report_data();
        
          $rowData = array();
          $data= array();
               $list =  $this->canteen_collection_reports->search_report_data();
          //print '<pre>'; print_r($list);die;
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {

                  $grand_total_amount = $grand_total_amount+$reports->total_amount;
                  $grand_total_discount = $grand_total_discount + $reports->discount;
                  $grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
                  $grand_balance_amount = $grand_balance_amount + $reports->balance;
                  $discount=$reports->discount;
                  
        if(1)
        {
          $sale_date = date('d-m-Y',strtotime($reports->purchase_date));  
        } 
        else
        {
          $sale_date = date('d-m-Y',strtotime($reports->sale_date));
        }

                  array_push($rowData,$reports->purchase_id,$reports->invoice_id,$sale_date,$reports->name,number_format($reports->total_amount,2),$discount,number_format($reports->paid_amount,2),number_format($reports->balance,2));
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
                  //print_r($reports_data);die;
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                      $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                      $objWorksheet->setCellValueByColumnAndRow(3,$row+1,'Total');
                      $objWorksheet->setCellValueByColumnAndRow(4,$row+1,number_format($grand_total_amount,2));
                      $objWorksheet->setCellValueByColumnAndRow(5,$row+1,number_format($grand_total_discount,2));
                      $objWorksheet->setCellValueByColumnAndRow(6,$row+1,number_format($grand_paid_amount,2));
                      $objWorksheet->setCellValueByColumnAndRow(7,$row+1,number_format($grand_balance_amount,2));
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
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=medicine_purchase_collection_report_".time().".csv");
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
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->general_model->branch_user_list();

        $data['profile_list'] = $this->opd->profile_list();
        $data['search_data'] = $this->session->userdata('medicine_purchase_collection_resport_search_data');
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                                   'from_date'=>$search_data['from_date'],
                                   'sale_no'=>$search_data['sale_no'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'refered_id'=>$search_data['refered_id'],
                                    'referred_by'=>$search_data['referred_by'],
                                     'referral_hospital'=>$search_data['referral_hospital'],
                                   'branch_id'=>$search_data['branch_id'],
                                   'employee'=>$search_data['employee'],
                                   'medicine_company'=>$search_data['medicine_company'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'from_date'=>'',
                                   "referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   'sale_no'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'branch_id'=>'',
                                   'employee'=>'',
                                   'medicine_company'=>'',
                                 );
        }  
      //  print_r($post);die;
          if(isset($post) && !empty($post))
          {
             
          $marge_post = array_merge($data['form_data'],$post);
          $this->session->set_userdata('medicine_purchase_collection_resport_search_data', $marge_post);
          }
        $this->load->view('canteen/canteen_purchase_report/advance_search',$data);
    }

    public function pdf_canteen_report()
    {   
       // unauthorise_permission('159','931');
        $data['print_status']="";
        $this->load->model('general/general_model');
          //$get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
        $data['date_time_status'] = '';
        $data['data_list'] = $this->canteen_collection_reports->search_report_data();
        $this->load->view('canteen/canteen_purchase_report/medicine_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_collection_report_".time().".pdf");
    }

    public function print_canteen_report()
    {   
       // unauthorise_permission('159','931');
        $data['print_status']="1";
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
          $date_time_status = $get_date_time_setting->date_time_status;
          $get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $data['data_list'] = $this->canteen_collection_reports->search_report_data();
        $this->load->view('canteen/canteen_purchase_report/medicine_report_html',$data); 
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
    public function print_canteen_collection_reports()
    { 
   // unauthorise_permission('159','931');
     $get = $this->input->get();
     $data['medicine_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['medicine_collection_list'] = $this->canteen_collection_reports->get_opd_collection_list_details($get);
     } 
     $this->load->view('canteen/canteen_purchase_report/list_medicine_collection',$data);  

    }


  public function print_vendor_balance_receipt($ids="", $purchase_id="",$paid='')
  {
      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');
       $this->load->model('purchase/purchase_model','purchase');
      $data['page_title'] = "Add purchase medicine";
      $get_detail_by_id= $this->canteen_collection_reports->get_by_id($purchase_id, $ids);
     
      $get_by_id_data=$this->canteen_collection_reports->get_all_detail_print($purchase_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['mode_payment']);
      $template_format= $this->purchase->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      $data['paid']= $paid;
    
      $this->load->view('canteen/canteen_purchase_report/print_template_medicine',$data);

  }
    
     
}
?>