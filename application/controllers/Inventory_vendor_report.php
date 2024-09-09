<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_vendor_report extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    auth_users();
    $this->load->model('inventory_vendor_report/Inventory_vendor_report_model','vendor_rate_comparison');  
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
       $data['page_title'] = 'Branch Allotment Item Summary Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       $this->load->model('stock_purchase/stock_purchase_model','stock_purchase');
       $data['vendor_list'] = $this->stock_purchase->vendor_list();
       $this->load->view('inventory_vendor_report/vendor_rate_comparison_report',$data);
    }

    public function ajax_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $data = array();
        $no = $_POST['start'];
        $search = $this->session->userdata('inventory_allot_items_search');
        $list = $this->vendor_rate_comparison->get_datatables();
        // echo"<pre>";print_r($list);die;
        $total_num = count($list);
        $i = 1;
        
        $grand_total_mrp =0;
	    $grand_total_purchase=0;
	    $grand_paid_discount=0;
	    $grand_total_amount=0;
	    
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
                            
            $get_items = $this->vendor_rate_comparison->get_items_allot($reports->id);
            $get_vendors = $this->vendor_rate_comparison->vendor_details($reports->purchase_id);
            //echo $get_items; die;
            ////////// Check list end ///////////// 
            $no++;
            
            $row = array(); 
            $row[] = $i;
            $row[] = $reports->branch_name;
            $row[] = $reports->item_code; 
            $row[] = $reports->item_name; 
            $row[] = $reports->serial_numbers;  
            $row[] = $get_items;//$reports->quantity;
            $row[] = $get_vendors; 
            $row[] = date('d-m-Y',strtotime($reports->created_date)); 
            $data[] = $row;
			
			 
            $i++;
        }

       $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor_rate_comparison->count_all(),
                        "recordsFiltered" => $this->vendor_rate_comparison->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    } 

    
    

    public function advance_search()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"","vendor_id"=>"");
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('inventory_allot_items_search', $merge);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('inventory_allot_items_search');
    }

    public function vendor_rate_comparison_excel()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         
         $fields = array('S.No.','Item Code','Item Name','Item Serial Number','Quantity','Vendor Name','Date');
         
         
        
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
             
          
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->vendor_rate_comparison->search_medicine_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            $i=1;
            foreach($list as $data_list)
            {
                    
                    
                    $get_items = $this->vendor_rate_comparison->get_items_allot($data_list->id);
            $get_vendors = $this->vendor_rate_comparison->vendor_details($data_list->purchase_id);
            
                    array_push($rowData,$i,$data_list->item_code,$data_list->item_name,$data_list->serial_numbers,$get_items,$get_vendors,date('d-m-Y',strtotime($data_list->created_date)));
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
        header("Content-Disposition: attachment; filename=inventory_vendor_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }



    public function vendor_rate_comparison_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->vendor_rate_comparison->search_medicine_data();
        $this->load->view('inventory_vendor_report/vendor_rate_comparison_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("inventory_vendor_report_".time().".pdf");
    }
    public function vendor_rate_comparison_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->vendor_rate_comparison->search_medicine_data();
      $this->load->view('inventory_vendor_report/vendor_rate_comparison_html',$data); 
    }


}
?>