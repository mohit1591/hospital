<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_part_summary_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('summary_reports/billing_part_summary_report_model','part_collection_report');
        $this->load->library('form_validation');
        //$this->output->enable_profiler(TRUE);

    }

    public function index()
    {   
        
        $this->session->unset_userdata('part_summary_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('part_collection_report');
        $start_date = date('d-m-Y');
        $end_date = date('d-m-Y');
       
        // End Defaul Search
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Particular Summary Report list';
        $this->load->model('general/general_model','general_model');
        
        $data['particulars_list'] = $this->general_model->particulars_list();  
        $this->load->view('billing_part_summary_report/list',$data);
    }
    
    public function ajax_list()
    {  
        
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->part_collection_report->get_datatables();
        //echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        foreach ($list as $reports) 
        {

               $grand_total_amount = $grand_total_amount+$reports->PersonalAmount;
               if($i==$total_num){
                  
               } 
            $no++;
            $row = array(); 
            $row[] = $i;  
            $row[] = $reports->particulars;
            //$row[] = date('d-m-Y', strtotime($reports->start_date));
            $row[] = $reports->amount;
            $row[] = $reports->PersonalCount+0;
            $row[] = number_format($reports->PersonalAmount,2);  
            $data[] = $row;         
           $tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = '';
              //$tot_row[] = '';    
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
                      "recordsTotal" => $this->part_collection_report->count_all(),
                      "recordsFiltered" => $this->part_collection_report->count_filtered(),
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
                                   
                                 );
         $this->session->set_userdata('part_collection_report',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('part_collection_report');
    }

    public function part_summary_report_excel()
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
          $fields = array('S. No.','Particular','Price', 'QTY','Net Amount');
              
          
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
                //$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
          
         
          $list = $this->part_collection_report->search_report_data();
          
    
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $m=1;
               foreach($list as $reports)
               {
                  
                  $ttl_paid= $ttl_paid +$reports->PersonalAmount;
                    array_push($rowData,$m,$reports->particulars,$reports->amount,$reports->PersonalCount,$reports->PersonalAmount);  
                  
                    
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

            
              
               
                   $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':G'.$row.'')->getFont()->setBold( true );
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,'Total');
                    
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_paid);
                    $objPHPExcel->setActiveSheetIndex(0);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
               
                
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=billing_part_summary_report_".time().".xls");   
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
            $this->session->set_userdata('part_collection_report',$post); 
        }
        $data['employee_list'] = $this->general_model->branch_user_list();
        //$data['profile_list'] = $this->opd->profile_list();
        $data['part_collection_report'] = $this->session->userdata('part_collection_report');
        if(isset($data['part_collection_report']) && !empty($data['part_collection_report']))
        {
           $billing_collection_search_data = $data['part_collection_report'];
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
        $this->load->view('billing_part_summary_report/advance_search',$data);
    }

    public function pdf_summary_report()
    {  
       
        $data['print_status']="";
        $data['data_list'] = $this->part_collection_report->search_report_data();
        $this->load->view('billing_part_summary_report/billing_part_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("billing_part_summary_report_report_".time().".pdf");
    }

    public function print_summary_report()
    {    
        $data['print_status']="1";
        $data['data_list'] = $this->part_collection_report->search_report_data();
        $this->load->view('billing_part_summary_report/billing_part_report_html',$data); 
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
    public function print_summary_report_reports()
    { 
      unauthorise_permission('88','569');
     $get = $this->input->get();
     $data['billing_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['billing_collection_list'] = $this->part_collection_report->get_billing_collection_details($get);
     } 
     $this->load->view('billing_part_summary_report/list_billing_part_report',$data);  

    }

    
     
}
?>