<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_profit_loss extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('medicine_report/medicine_profit_loss_model','medicinequantityreport');
        $this->load->library('form_validation');
    }

    public function medicine_report()
    {   
        $this->session->unset_userdata('search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('search_data');
        $data['form_data'] = array('start_date'=>'', 'end_date'=>'');
        $data['page_title'] = 'Medicine Profit Loss Report list'; 
        $this->load->view('medicine_report/medicine_profit_loss',$data);
    }
    public function expenses()
    {
        //unauthorise_permission('43','252');
        $data['employee_list'] = $this->reports->employee_list();
        $data['page_title'] = 'Expenses Reports';
        $this->load->view('reports/expense_report',$data);
    }
     public function medicine_quantity_report(){
      
       //unauthorise_permission('42','245');
       //$data['employee_list'] = $this->reports->employee_list();
             $this->load->model('general/general_model','general_model');
             $get= $this->input->get();
       $data['page_title'] = 'Medicine Quantity Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] =   date('d-m-Y'); 
       $this->load->view('medicinequantityreport/medicinequantityreport',$data);
    }
    public function next_appoitment(){
      
      // unauthorise_permission('104','647');
      // $data['next_appotment_list'] = $this->reports->next_appotment_list();
       $data['page_title'] = 'Next Appointment Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('reports/next_appoitment_report',$data);
    }
    public function medicine_collections(){
      $data['employee_list'] = $this->reports->employee_list();
      $data['page_title'] = 'Medicine Collections Reports';
      $this->load->view('reports/medicine_collection_report',$data);
    }

    public function ajax_list()
    {  
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->reports->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $reports) {
         // print_r($reports);die;
            ////////// Check  List /////////////////
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
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script; 
            $row[] = $reports->lab_reg_no;  
            $row[] = date('d-m-Y',strtotime($reports->booking_date));  
            $row[] = $reports->patient_name;  
            $row[] = $reports->doctor_name;  
            //$row[] = $reports->department;  
            $row[] = $reports->total_amount;  
            $row[] = $reports->discount;  
            $row[] = $reports->net_amount;  
            $row[] = $reports->paid_amount;  
            $row[] = $reports->balance;  
            $btn_edit = ' <a class="btn-custom" href="'.base_url("test/edit_booking/".$reports->id).'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            $btn_delete = '';  
            $row[] = $btn_edit.$btn_delete;         
            $data[] = $row;
            $i++;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->reports->count_all(),
                "recordsFiltered" => $this->reports->count_filtered(),
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
                'referral_doctor'=>'',
                'dept_id'=>'',
                'patient_code'=>'',
                'patient_name'=>'',
                'mobile_no'=>'',
                'end_date'=>$post['end_date'],
                'attended_doctor'=>'',
                'profile_id'=>'',
                'sample_collected_by'=>'',
                'staff_refrenace_id'=>''
              );
         $this->session->set_userdata('search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('search_data');
    }
    
    public function report_excel()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Sr.No.','Medicine Name','Batch No.','Qty','Sale Amt','Sale Ret Amt','Purchase Amt','Profit');
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
        $get = $this->input->get();
        //$list =  $this->medicinequantityreport->medicinequantityreport_list($get);  
        $list =  $this->medicinequantityreport->medicine_profit_loss_report_list($get);

        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            $k=1;
            $total_sale=0;
            $total_purchase=0;
            $total_profit=0;
            $total_sale_return=0;
            $total_purchasetotalprice=0;
            foreach($list as $medicines)
            {


                $med_name = trim($medicines['medicine_name']);
                

                if(!empty($medicines['purchase_total_price']))
                {
                  $purchasetotalprice = $medicines['purchase_total_price']*$medicines['sale_total_qty'];
                }
                else
                {
                  $purchasetotalprice = $medicines['opening_stock_price']*$medicines['sale_total_qty'];
                }





                $total_purchase_profit = $medicines['sale_total_price']-$purchasetotalprice;
                $total_med_profit = $total_purchase_profit-$medicines['sale_return_total_price'];
          


                array_push($rowData,$k,$med_name,$medicines['batch_no'],abs($medicines['sale_total_qty']),number_format(abs($medicines['sale_total_price']),2),number_format($medicines['sale_return_total_price'],2),number_format($purchasetotalprice,2),number_format($total_med_profit,2));


                $total_sale=$total_sale+$medicines['sale_total_price'];
    
                $total_sale_return=$total_sale_return+$medicines['sale_return_total_price'];
                
                $total_purchasetotalprice = $total_purchasetotalprice+$purchasetotalprice;

                $total_profit=$total_profit+$total_med_profit;

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


                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,number_format($total_sale,2));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,number_format($total_sale_return,2));

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,number_format($total_purchasetotalprice,2));

                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,number_format($total_profit,2));
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );  
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


            
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=medicine_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }
    public function report_excel20191106()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Sr.No.','Medicine Code','Medicine Name','Batch No.','Pur. Rate','Sale Rate');
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
           
            $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $col++;
            $row_heading++;
        }
        $get = $this->input->get();
        $list =  $this->medicinequantityreport->medicinequantityreport_list($get);  
        //$this->reports->search_report_data($get);
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            $k=1;
            foreach($list as $medicines)
            {
                 $med_name = trim($medicines['medicine_name']);
                   
                  

                array_push($rowData,$k,$medicines['medicine_code'],$med_name,$medicines['purchsase_quantity']['batch_no'],abs($medicines['purchsase_quantity']['total_amt']),abs($medicines['sale_quantity']['total_amt']));
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
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=opd_collection_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function next_appoitment_excel()
    {
        $get = $this->input->get();
        $this->load->model('general/general_model','general_model');
        $get_branch_detail= $this->general_model->get_branch_according_ids($get['branch_id']);

        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        // print_r($objWorksheet);die;
            $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();  
          $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          
          /*$objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $objWorksheet->setCellValueByColumnAndRow(4,$row+1,$get_branch_detail[0]->branch_name);
          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,$get['start_date']);
          $objWorksheet->setCellValueByColumnAndRow(7,$row+1,$get['end_date']);*/
          $objPHPExcel->getActiveSheet()->mergeCells("B1:D1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('B1',ucfirst($get_branch_detail[0]->branch_name));
          $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setSize(16);

          $objPHPExcel->getActiveSheet()->mergeCells("E1:F1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('E1',date('d/m/y',strtotime($get['start_date'])).' To '.date('d/m/y',strtotime($get['end_date'])));
          $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setSize(10);


          $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
          $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
          $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
          $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
          
         // Field names in the first row
         $fields = array('S.No.','Date','PATIENT NAME','DISEASE','ADD & PH. NO.','LAST FOLLOW UP DT','NEXT FOLLOW UP DT','REMARK');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, $field);
            $col++;
        }
       
        $list = $this->reports->next_appotment_list($get);
         
        //print '<pre>';print_r($list);die;
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=1;
            foreach($list as $reports)
            {
           
              
              if($reports->city!='' && $reports->mobile_no && $reports->address=''){
                $add=$reports->city.'-'.$reports->address.'-'.$reports->mobile_no;

              }elseif($reports->city!='' && $reports->address!='' && $reports->mobile_no!=''){
                $add=$reports->address.'-'.$reports->city ;
              }elseif($reports->address!='' && $reports->mobile_no!=''){
                $add=$reports->address.'-'.$reports->mobile_no;
              }
              elseif($reports->city!='' && $reports->mobile_no!=''){
                $add=$reports->address.'-'.$reports->mobile_no;
              }
              else{
                $add=$reports->mobile_no;
              }
              $next_app_date ='';
              if($reports->next_app_date!='0000-00-00' && $reports->next_app_date!='1970-01-01')
              {
                $next_app_date = date('d/m/y',strtotime($reports->next_app_date));
              }

                array_push($rowData,$i,date('d/m/y',strtotime($reports->created_date)),$reports->patient_name,$reports->dise,$add,date('d/m/y',strtotime($reports->booking_date)),$next_app_date,'');
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
        $row = 5;
        if(!empty($data))
        {
            foreach($data as $reports_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=next_appoitment_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
          
    }

    public function opd_report_csv()
    {
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('OPD No.','Booking Date','Patient Name','Doctor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
  
        $list = $this->reports->search_report_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $reports)
            {
              
                array_push($rowData,$reports->booking_code,date('d-m-Y',strtotime($reports->booking_date)),$reports->patient_name,$reports->doctor_name,$reports->total_amount,$reports->discount,$reports->net_amount,$reports->paid_amount,$reports->balance);
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
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=opd_collection_report_".time().".csv");
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
        $this->load->model('test/test_model','test');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('search_data',$post); 
        }
        $data['referal_doctor_list'] = $this->test->referal_doctor_list();
        $data['attended_doctor_list'] = $this->test->attended_doctor_list();
        //$data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->test->employee_list();
        $data['profile_list'] = $this->test->profile_list();
        $data['search_data'] = $this->session->userdata('search_data');
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                 'start_date'=>$search_data['start_date'],
                 'referral_doctor'=>$search_data['referral_doctor'],
                 'dept_id'=>$search_data['dept_id'],
                 'patient_code'=>$search_data['patient_code'],
                 'patient_name'=>$search_data['patient_name'],
                 'mobile_no'=>$search_data['mobile_no'],
                 'end_date'=>$search_data['end_date'],
                 'attended_doctor'=>$search_data['attended_doctor'],
                 'profile_id'=>$search_data['profile_id'],
                 'sample_collected_by'=>$search_data['sample_collected_by'],
                 'staff_refrenace_id'=>$search_data['staff_refrenace_id']
               );
        }
        else
        {
            $data['form_data'] = array(
                       'start_date'=>'',
                       'referral_doctor'=>'',
                       'dept_id'=>'',
                       'patient_code'=>'',
                       'patient_name'=>'',
                       'mobile_no'=>'',
                       'end_date'=>'',
                       'attended_doctor'=>'',
                       'profile_id'=>'',
                       'sample_collected_by'=>'',
                       'staff_refrenace_id'=>''
                     );
        }  
        $this->load->view('reports/advance_search',$data);
    }

    public function print_q_reports()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('medicine_profit_loss/medicine_profit_loss_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_profit_loss_report_".time().".pdf");
    }

    public function print_reportsold()
    { 
        $get = $this->input->get();
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model','general_model');
        $data['medicine_list'] = $this->medicinequantityreport->medicinequantityreport_list($get); 
        $data['get'] = $get;
        $this->load->view('medicine_report/medicine_profit_loss_data',$data);  
        //branch_collection_list
    }
    
     public function print_reports()
    { 
        $get = $this->input->get();
        $users_data = $this->session->userdata('auth_users');
        $data['medicine_list'] = $this->medicinequantityreport->medicine_profit_loss_report_list($get); 
        $data['get'] = $get;
        $this->load->view('medicine_report/medicine_profit_loss_report',$data);  
        //branch_collection_list
    }




}
?>