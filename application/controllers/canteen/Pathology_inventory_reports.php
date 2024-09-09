<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_inventory_reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('canteen/reports/pathology_inventory_report_model','reports');
        $this->load->library('form_validation');
    }

    public function index()
    {   

        unauthorise_permission('323','1936');
        $this->session->unset_userdata('search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('search_data');
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
            $end_date =  date('d-m-Y');
        }
        // End Defaul Search
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Pathology Inventory Report List';
        $this->load->model('general/general_model');
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $data['item_list']= $this->general_model->get_item_list();
        // echo '<pre>'; print_r($data['item_list']);die;
        $this->load->view('canteen/reports/list_pathology_inventory_report_data',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission('323','1936');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->reports->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $total_amount = '0.00';
        $total_discount = '0.00';
        $total_net_amount = '0.00';
        $total_paid_amount = '0.00';
        $total_balance = '0.00';
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
            $row[] = $reports->patient_code;   
            $row[] = $reports->patient_name; 
            $row[] = $reports->doctor_hospital_name;   
            $row[] = $reports->item;  
            $row[] = $reports->qty;  
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
              $users_data = $this->session->userdata('auth_users'); 
              $branch_id = $users_data['parent_id'];

              if(isset($post['branch_id']) && !empty($post['branch_id']))
              {
                $branch_id = $post['branch_id'];
              }
              $search_data =  array(
                                    'branch_id'=>$branch_id,
                                    'start_date'=>$post['start_date'],
                                    'item_name'=>$post['item_name'],
                                    'end_date'=>$post['end_date'],
                                    
                                 );
         $this->session->set_userdata('search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('search_data');
    }

    public function path_report_excel()
    {
      
        unauthorise_permission('323','1937');
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // added on 31-jan-2018
            $amt_ttl=0;
           
        // added on 31-jan-2018
        // Field names in the first row
        $fields = array('Lab Ref. No.','Booking Date','UHID No.','Patient Name','Referred By','Item Name','Qty');
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
              $amt_ttl=$reports->amount+$amt_ttl;
               
                array_push($rowData,$reports->lab_reg_no,date('d-m-Y',strtotime($reports->booking_date)),$reports->patient_code,$reports->patient_name,$reports->doctor_hospital_name,$reports->item,$reports->qty);
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
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
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
              // added on 31-jan-2018
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':J'.$row.'')->getFont()->setBold( true );
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row.'','Total');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row.'',$amt_ttl);
                
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
              // added on 31-jan-2018
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
        // Sending headers to force the user to download the file
         header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=Pathology_inventory_report_".time().".xls"); 
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
      
     
    }

    public function path_report_csv()
    {
        unauthorise_permission('323','1938');
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Lab Ref. No.','Booking Date','UHID No.','Patient Name','Referred By','Item Name','Qty');
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
          // added on 31-jan-2018
            $amt_ttl=0;
           
          // added on 31-jan-2018
            $i=0;
            foreach($list as $reports)
            {
               $amt_ttl=$reports->amount+$amt_ttl;
                array_push($rowData,$reports->lab_reg_no,date('d-m-Y',strtotime($reports->booking_date)),$reports->patient_code,$reports->patient_name,$reports->doctor_hospital_name,$reports->item,$reports->qty);
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
              // added on 31-jan-2018
                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':J'.$row.'')->getFill()->applyFromArray(array( 'type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => '37ac77' )  ));
                // $objPHPExcel->getActiveSheet()->mergeCells('A'.$row.':E'.$row.''); 
                // $objPHPExcel->getActiveSheet()->setCellValue('F'.$row.'','Total');
                // $objPHPExcel->getActiveSheet()->setCellValue('G'.$row.'',$amt_ttl);
          
              // added on 31-jan-2018
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=Pathology_inventory_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
            ob_end_clean();
            $objWriter->save('php://output');
          }
    
    }

    public function pdf_path_report()
    {    
        unauthorise_permission('323','1939');
        $data['print_status']="";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('canteen/reports/pathology_inventory_report_html',$data);
        $html = $this->output->get_output();

        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("pathology_inventory_report_".time().".pdf");
    }

    public function print_path_report()
    {   
        unauthorise_permission('323','1940');
        $data['print_status']="1";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('canteen/reports/pathology_inventory_report_html',$data); 
    }
}
?>