<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_kit_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('vaccination_kit_history/kit_history_model');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        $data['dept_type'] = $this->uri->segment('4');
        
        $data['page_title'] = 'Vaccination Kit History'; 
        $data['child_branch_list'] = $this->session->userdata('sub_branches_data');
        $this->load->view('vaccination_kit_history/list',$data);
    }

    public function ajax_list($pack_id="0",$type="0")
    {  
      $users_data = $this->session->userdata('auth_users'); 
      $sub_branch_details = $this->session->userdata('sub_branches_data'); 
      $list = $this->kit_history_model->get_datatables($pack_id,$type);

        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list); 
        foreach ($list as $history) {
         // print_r($simulation);die;
            $no++;
            $row = array(); 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
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
               
            if($type==2)
            {
              $row[] = $i;
              $row[] = $history->from_branch;
              $row[] = $history->to_branch;
              $row[] = $history->package_name;  
            }
            else if($type==0 || $type==1)
            {
              $row[] = $history->booking_code;
              $row[] = $history->patient_code;
              $row[] = $history->patient_name; 
              $row[] = $history->package_name; 
              $row[] = $history->credit;  
            } 
            
            if($type==3){
                 $row[] = $i;
                 $row[] = $history->package_name; 
                $row[] = $history->amount;
                 $row[] = $history->debit; 
            }
            else if($type==2){
                $row[] = $history->credit;
             } 
            $row[] = date('d-M-Y',strtotime($history->created_date)); 
            $btnedit='';
            $btndelete='';  
            if($type==3){
               $btnedit ='<button type="button" class="btn_custom" id="edit_qty" onclick="package_quantity_edit('.$history->kit_id.','.$history->id.');"><i class="fa fa-pencil"></i>Edit</button>';

            }
            $row[]=$btnedit;
            
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->kit_history_model->count_all(),
                        "recordsFiltered" => $this->kit_history_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
     public function add_vaccination_kit_quantity($kit_id="",$opt="",$row_id=""){
    
     $data['page_title'] = 'Add Vaccination Kit Quantity';
     $post = $this->input->post();
     $result = $this->kit_history_model->get_by_id($kit_id); 
    
     $data['form_data'] = array(
          'data_id'=>$result['id'],
          'medicine_kit_name'=>$result['title'],
          'opt'=>$opt,
          'row_id'=>$row_id
     ); 
     if(isset($post) && !empty($post)){
          $data['form_data'] = $this->validate_medicine_kit_data();
          if($this->form_validation->run() == TRUE){
               $this->kit_history_model->add_vaccination_kit_quantity($kit_id,$opt,$row_id);
               echo 1;
               return false;
                        
          }else{
                         
               $data['form_error'] = validation_errors(); 
          }
     }


          $this->load->view('vaccination_kit_history/vaccination_kit_quantity_add',$data);
    }
   public function validate_vaccination_kit_data(){
       $post = $this->input->post();
       $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
               
               $data['form_data'] = array(
                   'data_id'=>$post['data_id'],
                   'vaccination_kit_name'=>$post['vaccination_kit_name'],
                   'quantity'=>$post['quantity'],
                   
               );    
               return $data['form_data'];
        }   


   }
///excel and othe //

  public function vaccination_kit_history_excel($package_id='',$type='')
  {
          
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          //opd start 
        if($type=='0')
        {
          $fields = array('OPD NO.','Patient Reg. No.','Patient Name','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
         // echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$vaccination_kit_history_report->booking_code,$vaccination_kit_history_report->patient_code,$vaccination_kit_history_report->patient_name,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //opd finish

      //Billing start
      if($type=='1')
      {
          $fields = array('Billing NO.','Patient Reg. No.','Patient Name','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
         // echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$vaccination_kit_history_report->booking_code,$vaccination_kit_history_report->patient_code,$vaccination_kit_history_report->patient_name,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //Billing End

      //Branch allotment start
      if($type=='2')
      {
          $fields = array('S.NO.','From Branch','To Branch','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$k,$vaccination_kit_history_report->from_branch,$vaccination_kit_history_report->to_branch,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //Branch allotment end
      //manage Kit quantity
      if($type=='3')
      {
          $fields = array('S.NO.','Vaccination Kit','Amount','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_medicine_kit_history_excel_data($package_id,$type);
          //echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$k,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->amount,$vaccination_kit_history_report->debit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //manage Kit quantity end
          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $medicine_kit_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $medicine_kit_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=vaccination_kit_history_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    public function vaccination_kit_history_csv($package_id='',$type='')
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          //opd start 
        if($type=='0')
        {
          $fields = array('OPD NO.','Patient Reg. No.','Patient Name','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
         // echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$vaccination_kit_history_report->booking_code,$vaccination_kit_history_report->patient_code,$vaccination_kit_history_report->patient_name,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //opd finish

      //Billing start
      if($type=='1')
      {
          $fields = array('Billing NO.','Patient Reg. No.','Patient Name','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
         // echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$vaccination_kit_history_report->booking_code,$vaccination_kit_history_report->patient_code,$vaccination_kit_history_report->patient_name,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //Billing End

      //Branch allotment start
      if($type=='2')
      {
          $fields = array('S.NO.','From Branch','To Branch','Package Name','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$k,$vaccination_kit_history_report->from_branch,$vaccination_kit_history_report->to_branch,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->credit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //Branch allotment end
      //manage Kit quantity
      if($type=='3')
      {
          $fields = array('S.NO.','Vaccination Kit','Amount','Quantity','Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          //echo "<pre>";print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $k=1;
               foreach($list as $vaccination_kit_history_report)
               {
                                 
                array_push($rowData,$k,$vaccination_kit_history_report->package_name,$vaccination_kit_history_report->amount,$vaccination_kit_history_report->debit,date('d-m-Y',strtotime($vaccination_kit_history_report->created_date)));
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
      }
      //manage Kit quantity end

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $medicine_kit_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $medicine_kit_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=vaccination_kit_history_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_vaccination_kit_history($package_id='',$type='')
    {    
        $data['print_status']="";


        //opd start 
        if($type=='0')
        {
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_opd_report_html',$data);
        }
        //opd finish
        //Billing start
        if($type=='1')
        {
          
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_billing_report_html',$data);

        }
        //Billing End
        //Branch allotment start
        if($type=='2')
        {
          
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_branch_report_html',$data);

        }
        //Branch allotment end
        //manage Kit quantity
        if($type=='3')
        {

        $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
        $this->load->view('vaccination_kit_history/vaccination_kit_history_branch_report_html',$data);    
        }
        //manage Kit quantity end
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vaccination_kit_history_report_".time().".pdf");
    }
    public function print_vaccination_kit_history($package_id='',$type='')
    {    
    
      //opd start 
        if($type=='0')
        {
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_opd_report_html',$data);
        }
        //opd finish
        //Billing start
        if($type=='1')
        {
          
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_opd_report_html',$data);

        }
        //Billing End
        //Branch allotment start
        if($type=='2')
        {
          
          $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
          $this->load->view('vaccination_kit_history/vaccination_kit_history_opd_report_html',$data);

        }
        //Branch allotment end
        //manage Kit quantity
        if($type=='3')
        {

        $data['data_list'] = $this->kit_history_model->get_vaccination_kit_history_excel_data($package_id,$type);
        $this->load->view('vaccination_kit_history/vaccination_kit_history_opd_report_html',$data);    
        } 
    }
    
   

}
?>