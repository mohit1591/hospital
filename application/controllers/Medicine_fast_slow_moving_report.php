<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_fast_slow_moving_report extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    auth_users();
    $this->load->model('medicine_report/medicine_fast_slow_model','medicine_fast_slow');  
    $this->load->library('form_validation');
  }

   public function index()
   {


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
       $data['page_title'] = 'Fast To Slow Moving Medicine Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       $this->load->view('medicine_report/fast_slow_move_report',$data);
    }

    public function ajax_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $data = array();
        $no = $_POST['start'];
        $search = $this->session->userdata('medicine_fast_slow_search');

        $list = $this->medicine_fast_slow->medicine_fast_slow_report_list();
        //$list = $this->medicine_fast_slow->get_datatables();
        $total_num = count($list);
       // echo"<pre>"; print_r($list);die;

        $i = 1;
        foreach($list as $reports) 
        {
    
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
            $row[] = $i;
            $row[] = $reports->medicine_code; 
            $row[] = $reports->medicine_name;  
            $row[] = $reports->purchase_qty;
            $row[] = $reports->sale_qty;    
            $data[] = $row;
            $i++;
        }

       $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_fast_slow->count_all(),
                        "recordsFiltered" => $this->medicine_fast_slow->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    } 

    
    

    public function advance_search()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"",);
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('medicine_fast_slow_search', $merge);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('medicine_fast_slow_search');
    }

    public function medicine_fast_slow_moving_excel()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('S.No.','Medicine Code','Medicine Name','Purchase Quatity','Sale Quantity');
        
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
          
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->medicine_fast_slow->search_medicine_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
        
            $n=1;
            $k=1;
            $i=1;
            foreach($list as $data_list)
            {

            //$total_medicine_count = $this->medicine_fast_slow->get_medicine_count($data_list->p_id);
   
            $row = array();
            if($k==1)
            {
              array_push($rowData,$k,$data_list->medicine_code,$data_list->medicine_name,$data_list->purchase_qty,$data_list->sale_qty);
                //$k++;
            }
            else
            {
             array_push($rowData,$k,$data_list->medicine_code,$data_list->medicine_name,$data_list->purchase_qty,$data_list->sale_qty);
                                                      
            }
            $k++;

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
        header("Content-Disposition: attachment; filename=medicine_fast_slow_moving_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }



    public function medicine_fast_slow_moving_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->medicine_fast_slow->search_medicine_data();
        $this->load->view('medicine_report/fast_slow_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_fast_slow_list_".time().".pdf");
    }
    public function medicine_fast_slow_moving_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_fast_slow->search_medicine_data();
      $this->load->view('medicine_report/fast_slow_html',$data); 
    }





   
    public function print_medicine_gst_reports()
    { 
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
     
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_gst_list'] = [];
      $data['branch_gst_list'] = $this->medicine_gst->branch_gst_list($get);

    $data['get'] = $get;
 
     $this->load->view('medicine_gst/fast_slow_report_data',$data);  
  }

       public function gst_report_excel()
    {
      $get= $this->input->get();
    
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
    if($get['section_type']==1 || $get['section_type']==2){
     $fields = array('Purchase No.','Invoice No.','Vendor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
    }
    if($get['section_type']==3 || $get['section_type']==4){
     $fields = array('Sale No.','Patient Name.','Doctor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
    }
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
    $users_data = $this->session->userdata('auth_users');

    $branch_list= $this->session->userdata('sub_branches_data');
    $parent_id= $users_data['parent_id'];
    $branch_ids = array_column($branch_list, 'id'); 
    // print_r($branch_ids );die;
    $data['branch_gst_list'] = [];
     $list = $this->medicine_gst->branch_gst_list($get);
    
      
      
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $reports)
            {

              if($get['section_type']==1 || $get['section_type']==2){
                 array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
              }
              
               if($get['section_type']==3 || $get['section_type']==4){
                array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
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
        header("Content-Disposition: attachment; filename=gst_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }


    public function gst_report_csv()
    {
       $get= $this->input->get();
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
        if($get['section_type']==1 || $get['section_type']==2){
     $fields = array('Purchase No.','Invoice No.','Vendor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
    }
    if($get['section_type']==3 || $get['section_type']==4){
      $fields = array('Sale No.','Patient Name.','Doctor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
    }
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
    $users_data = $this->session->userdata('auth_users');
    
    $branch_list= $this->session->userdata('sub_branches_data');
    $parent_id= $users_data['parent_id'];
    $branch_ids = array_column($branch_list, 'id'); 
    // print_r($branch_ids );die;
    $data['branch_gst_list'] = [];
      $list = $this->medicine_gst->branch_gst_list($get);
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $reports)
            {
              
               if($get['section_type']==1 || $get['section_type']==2){
                 array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);

              }
              
               if($get['section_type']==3 || $get['section_type']==4){
                array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
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
}
?>