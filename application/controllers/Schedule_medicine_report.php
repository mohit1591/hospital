<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_medicine_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('medicine_report/Schedule_medicine_report_model','medicine_report');
        $this->load->model('medicine_stock/medicine_stock_model','medicine_stock');
        $this->load->library('form_validation');
    }

    public function index()
    {
          
    }

    public function schedule_medicine_qty_list()
    {
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('schmed_qty_search');
        $list = $this->medicine_report->get_datatables();

//echo "<pre>"; print_r($list); exit;

        $data = array();
        $no = $_POST['start'];
        $i = 1;

        $n=1;
        $k=1;
    $sale_id_arr = [];    
    foreach ($list as $data_list) 
      {

    // $qty_data = $this->medicine_report->get_batch_med_qty($data_list->m_id,$data_list->batch_no);
       
    // print_r($qty_data); exit;

        $no++;
        $row = array(); 
         $sale_no = '';
         $patient_name = '';
         $bill_date = '';
         $kk = '';
         if(!in_array($data_list->sale_id, $sale_id_arr))
         {
             $sale_no = $data_list->sale_no;
             $patient_name = $data_list->patient_name;
             $bill_date = date('d-m-Y h:i A', strtotime($data_list->created)); 
             $kk = $k;
         }
         $doctor_name = '';
         if($data_list->referred_by==0)
         {
             if(!empty($data_list->doctor_name))
             {
                 $doctor_name = $data_list->doctor_name;
             }
         }
         else if($data_list->referred_by==1)
         {
             if(!empty($data_list->hospital_name))
             {
                 $doctor_name = $data_list->hospital_name;
             }
         }
         
         
        $row[] = $kk;
        $row[] = $sale_no; 
        $row[] = $doctor_name;
        $row[] = $patient_name;
        $row[] = $data_list->medicine_name;
        $row[] = $data_list->qty;
        $row[] = $data_list->company_name;
        $row[] = $data_list->batch_no;
        $row[] = date('d-m-Y', strtotime($data_list->expiry_date));
        $row[] = $bill_date; 
        if(!in_array($data_list->sale_id, $sale_id_arr))
        {
            $k++;
            $sale_id_arr[] = $data_list->sale_id; 
        }

       
        $data[] = $row;
        $i++;
   }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_report->count_all(),
                        "recordsFiltered" => $this->medicine_report->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    
    }

    public function advance_search()
    {
        
        $post = $this->input->post();

 // echo "<pre>"; print_r($post);    


        $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model','general_model');
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list();

        $data['form_data'] = array("start_date"=>"","end_date"=>"", "type"=>"");
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('schmed_qty_search', $merge);
        }


    }

    public function reset_search()
    {
        $this->session->unset_userdata('schmed_qty_search');

    }

    public function schedule_medicine_report_excel()
    {
        $search_query = $this->input->get('search_query');
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('S.No.','Bill No.', 'Doctor/Hospital Name', 'Patient Name', 'Medicine Name', 'Quantity', 'Manuf. Company', 'Batch No.', 'Exp. Date', 'Bill Date');
        
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              // $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
          
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->medicine_report->search_medicine_data($search_query);
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
        
            $n=1;
            $k=1;
            $i=1;
            
            foreach($list as $data_list)
            {
                 $sale_no = '';
                 $patient_name = '';
                 $bill_date = '';
                 $kk = '';
                 if(!in_array($data_list->sale_id, $sale_id_arr))
                 {
                     $sale_no = $data_list->sale_no;
                     $patient_name = $data_list->patient_name;
                     $bill_date = date('d-m-Y h:i A', strtotime($data_list->created)); 
                     $kk = $k;
                 }
                 $doctor_name = '';
                 if($data_list->referred_by==0)
                 {
                     if(!empty($data_list->doctor_name))
                     {
                         $doctor_name = $data_list->doctor_name;
                     }
                 }
                 else if($data_list->referred_by==1)
                 {
                     if(!empty($data_list->hospital_name))
                     {
                         $doctor_name = $data_list->hospital_name;
                     }
                 }
                 
                array_push($rowData,$kk,$sale_no,$doctor_name,$patient_name,$data_list->medicine_name,$data_list->qty,$data_list->company_name,$data_list->batch_no, date('d-m-Y', strtotime($data_list->expiry_date)), $bill_date);
            if(!in_array($data_list->sale_id, $sale_id_arr))
            {
                $k++;
                $sale_id_arr[] = $data_list->sale_id; 
            }
                
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
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=schedule_medicine_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }


    public function schedule_medicine_report_pdf()
    {   
        $search_query = $this->input->get('search_query');
        $data['print_status']="";
        $this->load->model('general/general_model');
        $get_date_time_setting = $this->general_model->get_date_time_setting('opd_collection_report');
        $data['date_time_status'] = $get_date_time_setting->date_time_status;
        $data['report_list'] = $this->medicine_report->search_medicine_data($search_query);
        $this->load->view('medicine_report/schedule_medicine_report_qty_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("schedule_medicine_report_pdf_".time().".pdf");
    }

    public function schedule_medicine_report_print()
    {    
    $search_query = $this->input->get('search_query');
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->search_medicine_data($search_query);
      //print_r($data['report_list']);die;
      $this->load->view('medicine_report/schedule_medicine_report_qty_html',$data); 

    }
      
}
?>