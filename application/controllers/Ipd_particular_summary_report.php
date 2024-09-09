<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_particular_summary_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('summary_reports/ipd_particular_summary_report_model','ipd_part_collection_report');
        $this->load->library('form_validation');
        //$this->output->enable_profiler(TRUE);

    }

    public function index()
    {   
        
        $this->session->unset_userdata('ipd_particular_summary_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('ipd_particular_summary_search_data');
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
       
        // End Defaul Search
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'','particulars_name'=>'');
        $data['page_title'] = 'IPD Particular Summary Report';
        $this->load->model('general/general_model','general_model');
        $data['particulars_list'] = $this->general_model->ipd_particulars_list(); 
        $data['charge_type_list'] = $this->general_model->room_charge_type_list(); 
        
        $this->load->view('ipd_part_summary_report/list',$data);
    }
    
    public function ajax_list()
    {  
        
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->ipd_part_collection_report->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        foreach ($list as $reports) 
        {

               $grand_total_amount = $grand_total_amount+$reports->debit;
               if($i==$total_num){
                  
               } 
                                 
            $no++;
            $row = array(); 
            $row[] = $i;  
            $row[] = $reports->booking_code; 
            $row[] = $reports->patient_name;
            $row[] = $reports->patient_code;
            $row[] = $reports->particular_name;
            $row[] = date('d-m-Y',strtotime($reports->start_date));
            
            $row[] = $reports->quantity;
            $row[] = $reports->debit;  
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
              
              
              $data[] = $tot_row; 
           }
           $i++;
        }
        
        $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->ipd_part_collection_report->count_all(),
                      "recordsFiltered" => $this->ipd_part_collection_report->count_filtered(),
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
                                   
                                   'end_date'=>$post['end_date'],
                                   'particulars'=>$post['particulars'],
                                   'particulars_name'=>$post['particulars_name'],
                                   'charge_type'=>$post['charge_type'],
                                   
                                 );
         $this->session->set_userdata('ipd_particular_summary_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('ipd_particular_summary_search_data');
    }

    public function ipd_part_summary_report_excel()
    {
        
         $users_data = $this->session->userdata('auth_users'); 
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          // Field names in the first row
            $fields = array('S. No.','IPD No.','Patient Name', 'Reg. No.','Particular','Date','Quantity','Amount');
          
         
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
          
          //$list = $this->ipd_part_collection_report->search_report_data();
          $bill_data = $this->ipd_part_collection_report->search_report_data();
    $list = $bill_data['self_bill_coll'];
    
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $m=1;
               foreach($list as $reports)
               {
                  
                    $ttl_paid= $ttl_paid + $reports->debit;
                    array_push($rowData,$m,$reports->booking_code,$reports->patient_name,$reports->patient_code,$reports->particular_name,date('d-m-Y',strtotime($reports->start_date)),$reports->quantity,$reports->debit);
                   
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
              
              
                   
                   $objPHPExcel->getActiveSheet()->getStyle('F'.$row.':I'.$row.'')->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,'Total');
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,$ttl_paid);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                    
               
                
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=ipd_part_summary_report_".time().".xls");   
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
        //$this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('ipd_particular_summary_search_data',$post); 
        }
        $data['employee_list'] = $this->general_model->branch_user_list();
        //$data['profile_list'] = $this->opd->profile_list();
        $data['ipd_particular_summary_search_data'] = $this->session->userdata('ipd_particular_summary_search_data');
        if(isset($data['ipd_particular_summary_search_data']) && !empty($data['ipd_particular_summary_search_data']))
        {
           $billing_collection_search_data = $data['ipd_particular_summary_search_data'];
           if(isset($billing_collection_search_data['start_date']) && !empty($billing_collection_search_data['start_date']))
           {
            $start_date=$billing_collection_search_data['start_date'];
           }
           else
           {
            $start_date='';
           }
           
            if(isset($billing_collection_search_data['end_date']) && !empty($billing_collection_search_data['end_date']))
           {
            $end_date=$billing_collection_search_data['end_date'];
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
        $this->load->view('ipd_part_summary_report/advance_search',$data);
    }

    public function pdf_ipd_part_summary_report()
    {  
        unauthorise_permission('88','568');  
        $data['print_status']="";
        //$data['data_list'] = $this->ipd_part_collection_report->search_report_data();
        
        
        $bill_data = $this->ipd_part_collection_report->search_report_data();
    $data['data_list'] = $bill_data['self_bill_coll'];
    $data['self_bill_coll_payment_mode'] = $bill_data['self_bill_coll_payment_mode'];
    
    
        $this->load->view('ipd_part_summary_report/ipd_part_collection_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ipd_part_summary_report_report_".time().".pdf");
    }

    public function print_ipd_part_collection_report()
    {    
        $data['print_status']="1";
        //$data['data_list'] = $this->ipd_part_collection_report->search_report_data();
        
        $bill_data = $this->ipd_part_collection_report->search_report_data();
    $data['data_list'] = $bill_data['self_bill_coll'];
    $data['self_bill_coll_payment_mode'] = $bill_data['self_bill_coll_payment_mode'];
    
        $this->load->view('ipd_part_summary_report/ipd_part_collection_report_html',$data); 
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
    public function print_old_ipd_part_collection_report()
    { 
      unauthorise_permission('88','569');
     $get = $this->input->get();
     $data['billing_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['billing_collection_list'] = $this->ipd_part_collection_report->get_billing_collection_details($get);
     } 
     $this->load->view('ipd_part_summary_report/list_ipd_part_collection_report',$data);  

    }

    
     
}
?>