<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itemwise_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('canteen/itemwisereport/itemwise_report_model','medicinequantityreport');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        $this->session->unset_userdata('search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('search_data');
        $data['form_data'] = array('start_date'=>'', 'end_date'=>'');
        $data['page_title'] = 'Medicine Quantity Report list'; 
        $this->load->view('canteen/itemwisereport/list',$data);
    }
    public function expenses()
    {
        //unauthorise_permission('43','252');
        $data['employee_list'] = $this->reports->employee_list();
        $data['page_title'] = 'Expenses Reports';
        $this->load->view('reports/expense_report',$data);
    }
     public function medicine_quantity_report(){
         
       $this->load->model('canteen/vendor/vendor_model','vendor');
             $get= $this->input->get();
       $data['page_title'] = 'Item wise  Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] =   date('d-m-Y'); 
       $data['vendor_list'] = $this->vendor->vendor_list(1); 
       $this->load->view('canteen/itemwisereport/itemwisereport',$data);
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
        $fields = array('Sr.No.','Medicine Code','Medicine Name','Pur. Qty','Pur. Amt','Pur. Ret. Qty','Pur. Ret. Amt','Sale QTY','Sale Amt','Sale Ret. QTY','Sale Ret. Amt');
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
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
                   if($get['module_type']==1)
                  { 
                    $med_name.= trim($medicines['med_name']);
                  }
                  if($medicines['purchsase_quantity']['total_qty']>0){ $pur_qty = $medicines['purchsase_quantity']['total_qty'];}else{ $pur_qty = '';}
                  if($medicines['purchsase_quantity']['total_qty']>0){$pur_amt =  $medicines['purchsase_quantity']['total_amt'];}else{$pur_amt= '';}

                  if($medicines['sale_return_quantity']['total_qty']>0){ $sale_ret = $medicines['sale_return_quantity']['total_qty'];} else{$sale_ret='';}

                  if($medicines['sale_return_quantity']['total_amt']>0){ $sale_ret_amt = $medicines['sale_return_quantity']['total_amt'];}else { $sale_ret_amt='';}

                array_push($rowData,$k,$medicines['medicine_code'],$med_name,$pur_qty,$pur_amt,abs($medicines['purchsase_return_quantity']['total_qty']),abs($medicines['purchsase_return_quantity']['total_amt']),abs($medicines['sale_quantity']['total_qty']),abs($medicines['sale_quantity']['total_amt']),$sale_ret,$sale_ret_amt);
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

    public function pdf_opd_report()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('reports/opd_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("opd_collection_report_".time().".pdf");
    }

    public function print_opd_report()
    {    
        $data['print_status']="1";
        $data['data_list'] = $this->reports->search_report_data();
        $this->load->view('reports/opd_report_html',$data); 
    }
     public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
          $dropdown = '';
               if(!empty($sub_branch_details)){
                    $dropdown .= '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" name ="sub_branch_id" ><option value="">Select Sub Branch</option><option value="all" >All</option></option><option  selected="selected"  value='.$users_data['parent_id'].'>Self</option>';
                 
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
    public function print_opd_expenses_reports()
    { 
     unauthorise_permission('43','252');
     $get = $this->input->get();
     $data['expense_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['all_expense_list'] = $this->reports->get_expenses_details($get);
     }
     $data['get'] = $get;
     $data['page_title'] ="OPD Expenses Reports"; 
     $this->load->view('reports/list_expenses_reports',$data);  

    }
    public function print_quantity_reports()
    { 
      //unauthorise_permission('42','245');
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      // $data['expense_list'] = $this->reports->get_expenses_details($get); 
      $this->load->model('general/general_model','general_model');
         $data['medicine_list'] = $this->medicinequantityreport->medicinequantityreport_list($get); 
        $data['get'] = $get;
      $this->load->view('canteen/itemwisereport/list_collection_report_data',$data);  
      //branch_collection_list
    }

    public function print_hospital_collection_reports()
    { 
      unauthorise_permission('218','1238');
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $data['expense_list'] = $this->reports->get_expenses_details($get); 
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['self_opd_collection_list'] = $this->reports->self_hospital_collection_list($get);
      $data['branch_collection_list'] = [];
      if(!empty($branch_ids))
      {
        $data['branch_collection_list'] = $this->reports->branch_hospital_collection_list($get,$branch_ids);
      }
      $data['get'] = $get;
      $this->load->view('reports/list_hospital_collection_report',$data);  
      //branch_collection_list
    }

    public function print_medicine_collection_reports()
    { 
      
      $get = $this->input->get();
      if($_GET['type']==1){
  
      $this->collection_medicine_excel($get);
      exit;
      //$this->load->view('reports/list_collection_report_data',$data);
      }
      if($_GET['type']==2){
      $this->collection_medicine_csv($get);
      exit;
      //$this->load->view('reports/list_collection_report_data',$data);
      }else{

      }
    
      

    }


    // function collection_medicine_excel($get)
    // {
     
    //   $branch_list= $this->session->userdata('sub_branches_data');
    //   $branch_ids = array_column($branch_list, 'id'); 
    //   $data['branch_collection_list'] = [];
    //   $data['self_collection_list'] = $this->reports->self_medicine_collection_list($get);
    //    if(!empty($branch_ids))
    //    {
    //     $data['branch_collection_list'] = $this->reports->medicine_branch_collection_list($get,$branch_ids);
    //    }

    
    //     $columnHeader = '';  
    //     $columnHeader = "Sr.No." . "\t" . "Date" . "\t" . "Branch Name" . "\t" . "Patient Name". "\t" . "Amount";
    //     $setData = '';
       
    //    print_r($data['branch_collection_list']);die;

       /* if(!empty($data['branch_collection_list']))
        {
        
           
            $rowData = "";
            $i = 1;   
            $branch_total = 0;  
            $count_branch = count($data['branch_collection_list']);
            foreach($data['branch_collection_list'] as $branchs)
            {
                $branch_total = $branch_total+$branchs->debit; 
                 $rowData =$i . "\t" .  date('d-m-Y', strtotime($branchs->created_date)). "\t" . $branchs->branch_name. "\t". $branchs->patient_name. "\t".$branchs->debit; 
                $setData .= trim($rowData) . "\n";  
                $i++;  
            }
        }*/
        //echo $setData;die;
        //header("Content-type: application/octet-stream");  
       // header("Content-Disposition: attachment; filename=collection_medicine_report_".time().".xls");  
       // header("Pragma: no-cache");  
       // header("Expires: 0");  

        //echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    // }

 function collection_excel($get)
    {
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('Sr.No.','Date','Branch Name','Doctor Name','Amount');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $get = $this->input->get();
        $data['expense_list'] = $this->reports->get_expenses_details($get); 
        $branch_list= $this->session->userdata('sub_branches_data');
        $branch_ids = array_column($branch_list, 'id');
        $data['branch_collection_list'] = [];
        $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
        $data['self_collection_list'] = $this->reports->self_collection_list($get);
        if(!empty($branch_ids))
        {
               $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
        }
        $rowData = array();
        $data= array();
        if(!empty($data['branch_collection_list'])){
           
                 
            $i=0;
            $branch_total = 0;  
            $count_branch = count($data['branch_collection_list']);
            foreach($data['branch_collection_list'] as $branchs)
            {
              
                array_push($rowData,$i+1,date('d-m-Y', strtotime($branchs->created_date)),$branchs->branch_name,$branchs->doctor_name,$branchs->debit);
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
            foreach($data as $branchs_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $branchs_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=collection_new_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
     // $data['expense_list'] = $this->reports->get_expenses_details($get); 
     // $branch_list= $this->session->userdata('sub_branches_data');
     // //print_r($branch_list);die;
     // $branch_ids = array_column($branch_list, 'id'); 
     // $data['branch_collection_list'] = [];
     // $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
     // $data['self_collection_list'] = $this->reports->self_collection_list($get);
     // if(!empty($branch_ids))
     //   {
     //     $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
     //   }


     //    $columnHeader = '';  
     //    $columnHeader = "Sr.No." . "\t" . "Date" . "\t" . "Branch Name" . "\t" . "Doctor Name". "\t" . "Amount";
     //    $setData = '';
     //    if(!empty($data['branch_collection_list']))
     //    {
        
           
     //        $rowData = "";
     //        $i = 1;   
     //        $branch_total = 0;  
     //        $count_branch = count($data['branch_collection_list']);
     //        foreach($data['branch_collection_list'] as $branchs)
     //        {
     //            $branch_total = $branch_total+$branchs->debit; 
     //             $rowData =$i . "\t" .  date('d-m-Y', strtotime($branchs->created_date)). "\t" . $branchs->branch_name. "\t". $branchs->doctor_name. "\t".$branchs->debit; 
     //            $setData .= trim($rowData) . "\n";  
     //            $i++;  
     //        }
     //    }
     //    //echo $setData;die;
     //    header("Content-type: application/octet-stream");  
     //    header("Content-Disposition: attachment; filename=collection_new_report_".time().".xls");  
     //    header("Pragma: no-cache");  
     //    header("Expires: 0");  

     //    echo ucwords($columnHeader) . "\n" . $setData . "\n"; 

        // exit;
      
       
    }

     function collection_csv($get)
    {

     // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('Sr.No.','Date','Branch Name','Doctor Name','Amount');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $get = $this->input->get();
        $data['expense_list'] = $this->reports->get_expenses_details($get); 
        $branch_list= $this->session->userdata('sub_branches_data');
        $branch_ids = array_column($branch_list, 'id');
        $data['branch_collection_list'] = [];
        $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
        $data['self_collection_list'] = $this->reports->self_collection_list($get);
        if(!empty($branch_ids))
        {
               $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
        }
        $rowData = array();
        $data= array();
        if(!empty($data['branch_collection_list'])){
           
                 
            $i=0;
            $branch_total = 0;  
            $count_branch = count($data['branch_collection_list']);
            foreach($data['branch_collection_list'] as $branchs)
            {
              
                array_push($rowData,$i+1,date('d-m-Y', strtotime($branchs->created_date)),$branchs->branch_name,$branchs->doctor_name,$branchs->debit);
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
            foreach($data as $branchs_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $branchs_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=collection_new_report_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }

     //  $data['expense_list'] = $this->reports->get_expenses_details($get); 
     // $branch_list= $this->session->userdata('sub_branches_data');
     // $branch_ids = array_column($branch_list, 'id'); 
     // $data['branch_collection_list'] = [];
     // $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
     // $data['self_collection_list'] = $this->reports->self_collection_list($get);
     //   if(!empty($branch_ids))
     //   {
     //     $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
     //   }
     //   $columnHeader = '';  
     //    $columnHeader = "Sr.No." . "\t" . "Date" . "\t" . "Branch Name" . "\t" . "Doctor Name". "\t" . "Amount". "\t" . "Branch";
     //    $setData = '';
     //    if(!empty($data['branch_collection_list']))
     //    {
     //  $rowData = "";
     //        $i = 1;   
     //        $branch_total = 0;  
     //        $count_branch = count($data['branch_collection_list']);
     //        foreach($data['branch_collection_list'] as $branchs)
     //        {
     //            $branch_total = $branch_total+$branchs->debit; 
     //             $rowData =$i . "\t" .  date('d-m-Y', strtotime($branchs->created_date)). "\t" . $branchs->branch_name. "\t". $branchs->doctor_name. "\t".$branchs->debit;
     //            $setData .= trim($rowData) . "\n";  
     //            $i++;  
     //        }
     //    }
     //    //echo $setData;die;
     //    header("Content-type: application/octet-stream");  
     //    header("Content-Disposition: attachment; filename=collection_new_report_".time().".csv");  
     //    header("Pragma: no-cache");  
     //    header("Expires: 0");  

     //    echo ucwords($columnHeader) . "\n" . $setData . "\n";

     //    exit; 
    }

     function collection_medicine_csv($get)
    {

       $data['expense_list'] = $this->reports->get_expenses_details($get); 
       $branch_list= $this->session->userdata('sub_branches_data');
       $branch_ids = array_column($branch_list, 'id'); 
       $data['branch_collection_list'] = [];
       $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
       $data['self_collection_list'] = $this->reports->self_collection_list($get);
       if(!empty($branch_ids))
       {
         $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
       }
        $columnHeader = '';  
        $columnHeader = "Sr.No." . "\t" . "Date" . "\t" . "Branch Name" . "\t" . "Patient Name". "\t" . "Amount";
        $setData = '';
        if(!empty($data['branch_collection_list']))
        {
        
           
            $rowData = "";
            $i = 1;   
            $branch_total = 0;  
            $count_branch = count($data['branch_collection_list']);
            foreach($data['branch_collection_list'] as $branchs)
            {
                $branch_total = $branch_total+$branchs->debit; 
                 $rowData =$i . "\t" .  date('d-m-Y', strtotime($branchs->created_date)). "\t" . $branchs->branch_name. "\t". $branchs->patient_name. "\t".$branchs->debit; 
                $setData .= trim($rowData) . "\n";  
                $i++;  
            }
        }
        //echo $setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=collection_new_report_".time().".csv");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; 
    }


    public function enquiry_source(){
       //unauthorise_permission('104','646');
       $data['page_title'] = 'Enquiry Source From Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('reports/source_report',$data);
    }


    


    public function print_source_reports()
    { 
      //unauthorise_permission('42','245');
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 
      $data['source_from_list'] = $this->reports->source_from_report_list($get);

      $data['branch_source_from_list'] = [];
      if(!empty($branch_ids))
      {
        $data['branch_source_from_list'] = $this->reports->branch_source_name_list($get,$branch_ids);
      }
      //echo "<pre>"; print_r($data['branch_source_from_list']); exit;
      $data['get'] = $get;
      $this->load->view('reports/list_source_from_data',$data);  

    }


    public function source_report_csv()
    {
         
          $users_data = $this->session->userdata('auth_users');
          $branch_list= $this->session->userdata('sub_branches_data');
          $parent_id= $users_data['parent_id'];
          $branch_ids = array_column($branch_list, 'id'); 

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


    public function source_report_excel()
    { 
          $users_data = $this->session->userdata('auth_users');
          $branch_list= $this->session->userdata('sub_branches_data');
          $parent_id= $users_data['parent_id'];
          $branch_ids = array_column($branch_list, 'id'); 
          
      // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
          $users_list =  $this->reports->all_branch_users($users_data['parent_id']);
          //echo "<pre>"; print_r($users_list); exit; 
          $user_name_array = array('a'=>'Source','b'=>'Total','c'=>'No. of Enq.','d'=>'No. of App.'); 
          if(!empty($users_list)){
            $i=0;
            //echo "<pre>";print_r($users_list);die;
            foreach($users_list as $key=>$value){
                
               $user_name_array[$value->user_id] =  $value->name;
              
            }
           
        }
      //print_r($user_name_array); exit;
            $fields = $user_name_array;
        //merge field and branch name
        //print_r($fields); exit;
        $column_total = count($fields);
        $col = 0;
        $t_col = 1;
        $alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'); 
        $a=0;
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(26);
        foreach ($fields as $field)
        {

          if($col<=3)
          {
            $objPHPExcel->getActiveSheet()->setCellValue($alphabet[$col].'1', $field); 
                  }
                  else
                  {
            $alpha_start = $alphabet[$a].'1';
            $alpha_end = $alphabet[$a+1].'1'; 
            $a= $a+1; 
            $objPHPExcel->getActiveSheet()->mergeCells($alpha_start.':'.$alpha_end);
            $objPHPExcel->getActiveSheet()->setCellValue($alpha_start,$field);
                  } 
        $col++;
        $a++;
        $t_col++;
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:AZ1")->getFont()->setSize(13);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getStyle('A1:AZ1')->getAlignment()->setWrapText(true); 
        //old code//
        $get = $this->input->get();
        $list = $this->reports->source_from_report_list($get); 
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
          $i=0;
          foreach($list as $reports)
          {
            if(!empty($fields))
            {
              $cell_vals = [];
              $cell_i = 0;
              $users_data='';
              foreach($fields as $ukey=>$report_cell)
              {
                if($cell_i==3)
                {
                  $cell_vals = array($reports->source,$reports->total,$reports->total_enquiry,$reports->total_booking);
                }
                else if($cell_i>3)
                {
                                     $source_id = $reports->source_id;
                                     $users_data =  $this->reports->all_user_source_report($get,$ukey,$source_id);
                                     $udata = array($users_data[0]->total_enquiry,$users_data[0]->total_booking);
                                     $cell_vals = array_merge($cell_vals,$udata);
                }
                               
                               $cell_i++;
              }
            }
            array_push($rowData,$cell_vals);
            $rowData = $rowData[0];
            //echo "<pre>";print_r($rowData);die; 
            $count = count($rowData);
            //$fields = array_values($fields);  
            $f= 0;
            $field_arr = [];
                        foreach($fields as $fld)
                        {
                          if($f>3)
                          {
                               $field_arr[] = $fld;
                               $field_arr[] = $fld.'1';
                          } 
                          else
                          {
                            $field_arr[] = $fld;
                          }
                          $f++;
                        }
            //echo "<pre>";print_r($field_arr);die;
            for($j=0;$j<$count;$j++)
            {
              if($j>3)
              { 
                               $data[$i][$field_arr[$j]] = $rowData[$j];  
              }
              else
              { 
                $data[$i][$field_arr[$j]] = $rowData[$j];
              } 
            }
            //print_r($data); exit;
            unset($rowData);
            $rowData = array();
            $i++;  
          }
        }
        //echo "<pre>"; print_r($data); exit;
      
      $row = 2;
      if(!empty($data))
      {
        foreach($data as $reports_data)
        {
          $col = 0;
          foreach ($field_arr as $field)
          { 
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
              $objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
              $col++;
          }
          $row++;
        }
        

        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
      }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=source_report_".time().".xls");
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