<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_authrize_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('discount_authrize_report/discount_authrize_report_model','reports');
        $this->load->library('form_validation');
    }

   
    public function authorize_persone_reports()
    {
        unauthorise_permission('42','245');
        $this->load->model('general/general_model','general_model');
         $data['patient_category_list'] = $this->general_model->patient_category_list();
        $data['authorize_person_list'] = $this->general_model->authrize_person_list();   
        $data['department'] = get_collecton_department();   
        $data['page_title'] = 'Authorize Discount Reports';
        $data['from_c_date'] = date('d-m-Y');
        $data['to_c_date'] = date('d-m-Y'); 
        $this->load->view('discount_authrize_report/collection_report',$data);
    }
    public function reset_date_search()
    {
       $this->session->unset_userdata('search_data');
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
  
    public function print_discount_reports()
    { 
      $get = $this->input->get();
      $departs = array(); 
      if(!empty($get['dept']) && $get['dept'] !='') 
      { 
        $dept = $get['dept']; 
        $departs = explode(',', $dept); 
      }

      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_collection_list'] = [];
      if(in_array(2, $departs) || empty($departs))  
      {
        $data['self_opd_collection_list'] = $this->reports->self_opd_collection_list($get);
      }
      if(in_array(4, $departs) || empty($departs))  
      {
        $data['self_billing_collection_list'] = $this->reports->self_billing_collection_list($get);
      }
      if(in_array(13, $departs) || empty($departs))  
      {
        //$data['self_ambulance_collection_list'] = $this->reports->self_ambulance_collection_list($get);
      }
      if(in_array(3, $departs) || empty($departs))  
      {
        $data['self_medicine_collection_list'] = $this->reports->self_medicine_collection_list($get);
      }
      if(in_array(3, $departs) || empty($departs))  
      {
        //$data['self_medicine_return_collection_list'] = $this->reports->self_medicine_return_collection_list($get);
      }
      if(in_array(5, $departs) || empty($departs))  
      {
        $data['self_ipd_collection_list'] = $this->reports->self_ipd_collection_list($get);
      }
      if(in_array(1, $departs) || empty($departs))  
      {
        //$data['pathology_doctor_collection_list'] = $this->reports->pathology_doctor_collection_list($get);
        $data['pathology_self_collection_list'] = $this->reports->pathology_self_collection_list($get);
      }
      if(in_array(7, $departs) || empty($departs))  
      {
        //$data['self_vaccination_collection_list'] = $this->reports->self_vaccination_collection_list($get);
      }
      if(in_array(8, $departs) || empty($departs))  
      {
        //$data['self_ot_collection_list'] = $this->reports->self_ot_collection_list($get);
      }
      if(in_array(10, $departs) || empty($departs)) 
      {
        //$data['self_blood_bank_collection_list'] = $this->reports->self_blood_bank_collection_list($get);
      }
      if(in_array(16, $departs) || empty($departs)) 
      {
        //$data['self_dialysis_collection_list'] = $this->reports->self_dialysis_collection_list($get);
      }
      
      $data['get'] = $get;
      $data['collection_tab_setting'] = $this->reports->get_collection_tab_setting();
       
	  $this->load->view('discount_authrize_report/list_discount_report_data',$data);
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
      $data['collection_tab_setting'] = $this->reports->get_collection_tab_setting();
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
       unauthorise_permission('104','646');
       $data['page_title'] = 'Enquiry Source From Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('reports/source_report',$data);
    }


    


    public function print_source_reports()
    { 
      unauthorise_permission('42','245');
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
    
    public function collections_day()
    {
        unauthorise_permission('42','245');
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();      
        $data['page_title'] = 'Day Wise Collections Reports';
        $data['from_c_date'] = date('d-m-Y');
        $data['to_c_date'] = date('d-m-Y'); 
        $this->load->view('reports/collection_day_report',$data);
    }
    
    /* Day wise Collection Reports */
    
    
    public function print_opd_collection_day_reports()
    { 
      $this->load->model('reports/reports_day_model','reports_day');
      unauthorise_permission('42','245');
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      
      
      //expense start 
      
      $data['normal_expense_list'] = $this->reports_day->get_exp_expenses_details($get); 
      $data['doc_com_list'] = $this->reports_day->get_exp_doctor_commission($get);
      $data['salary_list'] = $this->reports_day->get_salary_expenses_details($get);
      $data['med_pur_list'] = $this->reports_day->get_medicine_expenses_details($get);
      $data['sale_ret_list'] = $this->reports_day->get_sale_return_expenses_details($get);
      $data['stock_list'] = $this->reports_day->get_stock_inventory_expenses_details($get);
      $data['vacc_pur_list'] = $this->reports_day->get_vacc_purchase_expenses_details($get);
      $data['vacc_sale_ret_list'] = $this->reports_day->get_vacc_sale_return_expenses_details($get);
      $data['opd_refund_list'] = $this->reports_day->get_opd_refund_expenses_details($get);
      $data['path_refund_list'] = $this->reports_day->get_path_refund_expenses_details($get);
      $data['med_refund_list'] = $this->reports_day->get_med_refund_expenses_details($get);
      $data['ipd_refund_list'] = $this->reports_day->get_ipd_refund_expenses_details($get);
      $data['ot_refund_list'] = $this->reports_day->get_ot_refund_expenses_details($get);
      $data['bb_refund_list'] = $this->reports_day->get_bb_refund_expenses_details($get);
      $data['ambulance_list'] = $this->reports_day->get_ambulance_expenses_details($get);
      $data['opd_bill_list'] = $this->reports_day->get_opd_bill_refund_expenses_details($get);
      $data['daycare_list'] = $this->reports_day->get_daycare_expenses_details($get);
      
      $data['dialysis_expense_list'] = $this->reports_day->get_dialysis_expenses_details($get);
      
      
      
      //expense end
      
       $data['self_ipd_collection_list'] = $this->reports_day->self_ipd_collection_list($get);
       // echo "<pre>";print_r($data['self_ipd_collection_list']); die;
        
        
      //$data['expense_list'] = $this->reports_day->get_expenses_details($get); 
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_collection_list'] = [];
      $data['self_opd_collection_list'] = $this->reports_day->self_opd_collection_list($get);
     // echo "<pre>"; print_r($data['self_opd_collection_list']);die();
      /* 14-10-2019 */
     $data['self_purchase_return_collection_list'] = $this->reports_day->self_purchase_collection_list($get);
       /* 06-11-2019  */
        $data['self_ambulance_collection_list'] = $this->reports_day->self_ambulance_collection_list($get);

        /* 06-11-2019 */
      $data['self_billing_collection_list'] = $this->reports_day->self_billing_collection_list($get);
      $data['self_medicine_collection_list'] = $this->reports_day->self_medicine_collection_list($get);

     
      $data['pathology_self_collection_list'] = $this->reports_day->pathology_self_collection_list($get);

      $data['self_vaccination_collection_list'] = $this->reports_day->self_vaccination_collection_list($get);
      $data['self_ot_collection_list'] = $this->reports_day->self_ot_collection_list($get);
     

      $data['self_blood_bank_collection_list'] = $this->reports_day->self_blood_bank_collection_list($get);
      
      $data['self_dialysis_collection_list'] = $this->reports_day->self_dialysis_collection_list($get);
      

      if(!empty($branch_ids))
      {
        $data['branch_collection_list'] = $this->reports_day->branch_opd_collection_list($get,$branch_ids);

        //echo "<pre>";print_r($data['branch_collection_list']);die;
        /* 06-11-2019 */
        $data['branch_ambulance_collection_list'] = $this->reports_day->branch_ambulance_collection_list($get,$branch_ids);
        /* 06-11-2019 */
        $data['branch_billing_collection_list'] = $this->reports_day->branch_billing_collection_list($get,$branch_ids);
        $data['branch_medicine_collection_list'] = $this->reports_day->branch_medicine_collection_list($get,$branch_ids);
       // $data['branch_medicine_return_collection_list'] = $this->reports_day->branch_medicine_return_collection_list($get,$branch_ids);
        
        $data['branch_vaccination_collection_list'] = $this->reports_day->branch_vaccination_collection_list($get,$branch_ids);
        $data['branch_ipd_collection_list'] = $this->reports_day->branch_ipd_collection_list($get,$branch_ids);

        $data['pathology_branch_collection_list'] = $this->reports_day->pathology_branch_collection_list($get,$branch_ids);
        $data['branch_ot_collection_list'] = $this->reports_day->branch_ot_collection_list($get,$branch_ids);

        /* blood bank collection report */
        $data['blood_bank_branch_collection_list'] = $this->reports_day->blood_bank_branch_collection_list($get,$branch_ids);
        /* branch blood bank */
        $data['dialysis_branch_collection_list'] = $this->reports_day->dialysis_branch_collection_list($get,$branch_ids);
        
      }
      $data['get'] = $get;
      $data['collection_tab_setting'] = $this->reports_day->get_collection_tab_setting();
      if(!empty($get['send']) && $get['send']=='preview')
      {
        $this->load->view('reports/list_collection_day_report_preview',$data);
      }
      else
      {
        $from_date = $get['start_date'];
        $to_date = $get['end_date'];
        
        $middle_replace_part=$this->load->view('reports/list_collection_day_report_data',$data,true);
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader('<div style="text-align: center; font-weight: bold;">
        Collection Report from '.$from_date.' To '.$to_date.'
        </div>');

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter('<div style="width:100%;  height:50px; border-top:solid 1px #000; text-align:center; font-weight:bold;">{PAGENO}/{nbpg}</div>'); 
        $pdfFilePath = 'collection_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }
      //branch_collection_list
    }

 public function print_opd_collection_day_reports20201221()
    { 
      $this->load->model('reports/reports_day_model','reports_day');
      unauthorise_permission('42','245');
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      
       $data['self_ipd_collection_list'] = $this->reports_day->self_ipd_collection_list($get);
       // echo "<pre>";print_r($data['self_ipd_collection_list']); die;
        
        
      //$data['expense_list'] = $this->reports_day->get_expenses_details($get); 
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_collection_list'] = [];
      $data['self_opd_collection_list'] = $this->reports_day->self_opd_collection_list($get);
     // echo "<pre>"; print_r($data['self_opd_collection_list']);die();
      /* 14-10-2019 */
     $data['self_purchase_return_collection_list'] = $this->reports_day->self_purchase_collection_list($get);
       /* 06-11-2019 */
        $data['self_ambulance_collection_list'] = $this->reports_day->self_ambulance_collection_list($get);

        /* 06-11-2019 */
      $data['self_billing_collection_list'] = $this->reports_day->self_billing_collection_list($get);
      $data['self_medicine_collection_list'] = $this->reports_day->self_medicine_collection_list($get);

     
      $data['pathology_self_collection_list'] = $this->reports_day->pathology_self_collection_list($get);

      $data['self_vaccination_collection_list'] = $this->reports_day->self_vaccination_collection_list($get);
      $data['self_ot_collection_list'] = $this->reports_day->self_ot_collection_list($get);
     

      $data['self_blood_bank_collection_list'] = $this->reports_day->self_blood_bank_collection_list($get);

      if(!empty($branch_ids))
      {
        $data['branch_collection_list'] = $this->reports_day->branch_opd_collection_list($get,$branch_ids);

        //echo "<pre>";print_r($data['branch_collection_list']);die;
        /* 06-11-2019 */
        $data['branch_ambulance_collection_list'] = $this->reports_day->branch_ambulance_collection_list($get,$branch_ids);
        /* 06-11-2019 */
        $data['branch_billing_collection_list'] = $this->reports_day->branch_billing_collection_list($get,$branch_ids);
        $data['branch_medicine_collection_list'] = $this->reports_day->branch_medicine_collection_list($get,$branch_ids);
       // $data['branch_medicine_return_collection_list'] = $this->reports_day->branch_medicine_return_collection_list($get,$branch_ids);
        
        $data['branch_vaccination_collection_list'] = $this->reports_day->branch_vaccination_collection_list($get,$branch_ids);
        $data['branch_ipd_collection_list'] = $this->reports_day->branch_ipd_collection_list($get,$branch_ids);

        $data['pathology_branch_collection_list'] = $this->reports_day->pathology_branch_collection_list($get,$branch_ids);
        $data['branch_ot_collection_list'] = $this->reports_day->branch_ot_collection_list($get,$branch_ids);

        /* blood bank collection report */
        $data['blood_bank_branch_collection_list'] = $this->reports_day->blood_bank_branch_collection_list($get,$branch_ids);
        /* branch blood bank */
        
      }
      $data['get'] = $get;
      $data['collection_tab_setting'] = $this->reports_day->get_collection_tab_setting();
      if(!empty($get['send']) && $get['send']=='preview')
      {
        $this->load->view('reports/list_collection_day_report_preview',$data);
      }
      else
      {
        $from_date = $get['start_date'];
        $to_date = $get['end_date'];
        
        $middle_replace_part=$this->load->view('reports/list_collection_day_report_data',$data,true);
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader('<div style="text-align: center; font-weight: bold;">
        Collection Report from '.$from_date.' To '.$to_date.'
        </div>');

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter('<div style="width:100%;  height:50px; border-top:solid 1px #000; text-align:center; font-weight:bold;">{PAGENO}/{nbpg}</div>'); 
        $pdfFilePath = 'collection_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }
      //branch_collection_list
    }
    
    
    public function print_advance_collection_reports()
    { 
      unauthorise_permission('42','245');
      $get = $this->input->get();

      $departs = array(); 
      if(!empty($get['dept']) && $get['dept'] !='') 
      { 
        $dept = $get['dept']; 
        $departs = explode(',', $dept); 
      }

      $users_data = $this->session->userdata('auth_users');
      $get['branch_id']=$users_data['parent_id'];
      $data['normal_expense_list'] = $this->reports->get_exp_expenses_details($get); 
      $data['doc_com_list'] = $this->reports->get_exp_doctor_commission($get);
      $data['salary_list'] = $this->reports->get_salary_expenses_details($get);
      if(in_array(3, $departs) || empty($departs))  
      {
        $data['med_pur_list'] = $this->reports->get_medicine_expenses_details($get);
        $data['sale_ret_list'] = $this->reports->get_sale_return_expenses_details($get);
      }
      if(in_array(6, $departs) || empty($departs))
      {
        $data['stock_list'] = $this->reports->get_stock_inventory_expenses_details($get);
      }
      if(in_array(7, $departs) || empty($departs))  
      {
        $data['vacc_pur_list'] = $this->reports->get_vacc_purchase_expenses_details($get);
        $data['vacc_sale_ret_list'] = $this->reports->get_vacc_sale_return_expenses_details($get);
      }
      if(in_array(2, $departs) || empty($departs))  
      {
        $data['opd_refund_list'] = $this->reports->get_opd_refund_expenses_details($get);
      }
      if(in_array(1, $departs) || empty($departs))  
      {
        $data['path_refund_list'] = $this->reports->get_path_refund_expenses_details($get);
      }
      if(in_array(3, $departs) || empty($departs))  
      {
        $data['med_refund_list'] = $this->reports->get_med_refund_expenses_details($get);
      }
      if(in_array(5, $departs) || empty($departs))  
      {
        $data['ipd_refund_list'] = $this->reports->get_ipd_refund_expenses_details($get);
      }
      if(in_array(8, $departs) || empty($departs))  
      {
        $data['ot_refund_list'] = $this->reports->get_ot_refund_expenses_details($get);
      }
      if(in_array(10, $departs) || empty($departs)) 
      {
        $data['bb_refund_list'] = $this->reports->get_bb_refund_expenses_details($get);
      }
      if(in_array(13, $departs) || empty($departs)) 
      {
        $data['ambulance_list'] = $this->reports->get_ambulance_expenses_details($get);
      }
      if(in_array(2, $departs) || empty($departs))  
      {
        $data['opd_bill_list'] = $this->reports->get_opd_bill_refund_expenses_details($get);
      }
      if(in_array(14, $departs) || empty($departs)) 
      {
        $data['daycare_list'] = $this->reports->get_daycare_expenses_details($get);
      }
      if(in_array(16, $departs) || empty($departs)) 
      {
        $data['dialysis_exp_list'] = $this->reports->get_dialysis_expenses_details($get);
      }
      
       


      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_collection_list'] = [];
      if(in_array(2, $departs) || empty($departs))  
      {
        $data['self_opd_collection_list'] = $this->reports->self_opd_collection_list($get);
      }
      if(in_array(14, $departs) || empty($departs)) 
      {
        $data['self_day_care_collection_list'] = $this->reports->self_day_care_collection_list($get);
      }

      /* 14-10-2019 */
      if(in_array(3, $departs) || empty($departs))  
      {
        $data['self_purchase_return_collection_list'] = $this->reports->self_purchase_collection_list($get);
      }
      if(in_array(13, $departs) || empty($departs)) 
      {
        $data['self_ambulance_collection_list'] = $this->reports->self_ambulance_collection_list($get);
      }
        /* 06-11-2019 */
      if(in_array(4, $departs) || empty($departs))  
      { 
        $data['self_billing_collection_list'] = $this->reports->self_billing_collection_list($get);
      }
      if(in_array(3, $departs) || empty($departs))  
      {
        $data['self_medicine_collection_list'] = $this->reports->self_medicine_collection_list($get);
        $data['self_medicine_return_collection_list'] = $this->reports->self_medicine_return_collection_list($get);
      }
      if(in_array(5, $departs) || empty($departs))  
      {  
        $data['self_ipd_collection_list'] = $this->reports->self_ipd_collection_list($get);
      }
      if(in_array(1, $departs) || empty($departs))  
      {
        $data['pathology_doctor_collection_list'] = $this->reports->pathology_doctor_collection_list($get);
        $data['pathology_self_collection_list'] = $this->reports->pathology_self_collection_list($get);
      }
      if(in_array(7, $departs) || empty($departs))  
      {
        $data['self_vaccination_collection_list'] = $this->reports->self_vaccination_collection_list($get);
      }
      if(in_array(8, $departs) || empty($departs))  
      {
        $data['self_ot_collection_list'] = $this->reports->self_ot_collection_list($get);
      }
      if(in_array(10, $departs) || empty($departs)) 
      {
        $data['self_blood_bank_collection_list'] = $this->reports->self_blood_bank_collection_list($get);
      }
      if(in_array(16, $departs) || empty($departs)) 
      {
       $data['self_dialysis_collection_list'] = $this->reports->self_dialysis_collection_list($get);
       //echo "<pre>"; print_r($data['self_dialysis_collection_list']); die;
      }
      /*09-05-20220*/
      if(!empty($branch_ids))
      {
        if(in_array(2, $departs) || empty($departs))  
        {
          $data['branch_collection_list'] = $this->reports->branch_opd_collection_list($get,$branch_ids);
        }
        if(in_array(14, $departs) || empty($departs)) 
        {
          $data['branch_day_care_collection_list'] = $this->reports->branch_day_care_collection_list($get,$branch_ids);
        }
        if(in_array(13, $departs) || empty($departs))  
        {
          $data['branch_ambulance_collection_list'] = $this->reports->branch_ambulance_collection_list($get,$branch_ids);
        }
        if(in_array(4, $departs) || empty($departs))  
        {
          $data['branch_billing_collection_list'] = $this->reports->branch_billing_collection_list($get,$branch_ids);
        }
        if(in_array(3, $departs) || empty($departs))  
        {
          $data['branch_medicine_collection_list'] = $this->reports->branch_medicine_collection_list($get,$branch_ids);
          $data['branch_medicine_return_collection_list'] = $this->reports->branch_medicine_return_collection_list($get,$branch_ids);
        }
        if(in_array(7, $departs) || empty($departs))  
        {
          $data['branch_vaccination_collection_list'] = $this->reports->branch_vaccination_collection_list($get,$branch_ids);
        }
        if(in_array(5, $departs) || empty($departs))  
        {
          $data['branch_ipd_collection_list'] = $this->reports->branch_ipd_collection_list($get,$branch_ids);
        }
        if(in_array(1, $departs) || empty($departs))  
        {
          $data['pathology_branch_collection_list'] = $this->reports->pathology_branch_collection_list($get,$branch_ids);
        }
        if(in_array(8, $departs) || empty($departs))  
        {
          $data['branch_ot_collection_list'] = $this->reports->branch_ot_collection_list($get,$branch_ids);
        }

        if(in_array(10, $departs) || empty($departs)) 
        {
          $data['blood_bank_branch_collection_list'] = $this->reports->blood_bank_branch_collection_list($get,$branch_ids);
        }
        if(in_array(16, $departs) || empty($departs)) 
        {
          $data['branch_dialysis_collection_list'] = $this->reports->branch_dialysis_collection_list($get,$branch_ids);
        }
        
      }
      $data['get'] = $get;
      $data['collection_tab_setting'] = $this->reports->get_collection_tab_setting();

        $from_date = $get['start_date'];
        $to_date = $get['end_date'];
          
        $middle_replace_part=$this->load->view('reports/adv_list_collection_report_data',$data,true);
        $this->load->library('m_pdf');
        //echo $middle_replace_part; die;
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader('<div style="text-align: center; font-weight: bold;">Collection Report from '.$from_date.' To '.$to_date.'</div>');

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter('<div style="width:100%;  height:50px; border-top:solid 1px #000; text-align:center; font-weight:bold;">{PAGENO}/{nbpg}</div>'); 
        $pdfFilePath = 'collection_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    
      //branch_collection_list
   }
   
   //new code 20 may 2020

    public function ledger_report(){
       unauthorise_permission('42','245');  
       $data['page_title'] = 'Ledger Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('reports/ledger_reports',$data);
    }

  
  
      public function print_ladgers_report()  
  { 
    unauthorise_permission('43','252'); 
    $get =$this->input->get();  
    $this->load->model('reports/reports_leder_model','ledger_report');  
     $data['report_list'] = []; 
     if(!empty($get['branch_id']))  
     {  
        $datas=array(); 
        $price = array(); 
        $opd_report_list = $this->ledger_report->self_opd_collection_list($get);  
          if(!empty($opd_report_list['self_coll'])) 
           {   foreach ($opd_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $dc_report_list = $this->ledger_report->self_day_care_collection_list($get);  
         if(!empty($dc_report_list['self_coll'])) 
           {   foreach ($dc_report_list['self_coll'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
        $pc_report_list = $this->ledger_report->self_purchase_collection_list($get);  
         if(!empty($pc_report_list['self_coll'])) 
           {   foreach ($pc_report_list['self_coll'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
        $amb_report_list = $this->ledger_report->self_ambulance_collection_list($get);  
         if(!empty($amb_report_list['self_coll']))  
           {   foreach ($amb_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $opb_report_list = $this->ledger_report->self_billing_collection_list($get);  
         if(!empty($opb_report_list['self_coll']))  
           {   foreach ($opb_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $med_report_list = $this->ledger_report->self_medicine_collection_list($get); 
         if(!empty($med_report_list['self_coll']))  
           {   foreach ($med_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $ipd_report_list = $this->ledger_report->self_ipd_collection_list($get);  
         if(!empty($ipd_report_list['self_coll']))  
           {   foreach ($ipd_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $path_report_lis = $this->ledger_report->pathology_self_collection_list($get);  
         if(!empty($path_report_lis['self_coll']))  
           {   foreach ($path_report_lis['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $vac_report_list = $this->ledger_report->self_vaccination_collection_list($get);  
         if(!empty($vac_report_list['self_coll']))  
           {   foreach ($vac_report_list['self_coll'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        $ot_report_list = $this->ledger_report->self_ot_collection_list($get);  
         if(!empty($ot_report_list['self_coll'])) 
           {   foreach ($ot_report_list['self_coll'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
        $bb_report_list = $this->ledger_report->self_blood_bank_collection_list($get);  
         if(!empty($bb_report_list['self_coll'])) 
           {   foreach ($bb_report_list['self_coll'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
          
      $normal_expense_list = $this->ledger_report->get_exp_expenses_details($get);  
      if(!empty($normal_expense_list['expense_list']))  
           {   foreach ($normal_expense_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $datasalary_list= $this->ledger_report->get_salary_expenses_details($get);  
      if(!empty($datasalary_list['expense_list']))  
           {   foreach ($datasalary_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $med_pur_list = $this->ledger_report->get_medicine_expenses_details($get);  
      if(!empty($med_pur_list['expense_list'])) 
           {   foreach ($med_pur_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $sale_ret_list = $this->ledger_report->get_sale_return_expenses_details($get);  
      if(!empty($sale_ret_list['expense_list']))  
           {   foreach ($sale_ret_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $stock_list = $this->ledger_report->get_stock_inventory_expenses_details($get); 
       if(!empty($stock_list['expense_list']))  
           {   foreach ($stock_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $doc_com_list = $this->ledger_report->get_exp_doctor_commission_exp($get);  
       if(!empty($doc_com_list['expense_list']))  
           {   foreach ($doc_com_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $vacc_pur_list = $this->ledger_report->get_vacc_purchase_expenses_details($get);  
       if(!empty($vacc_pur_list['expense_list'])) 
           {   foreach ($vacc_pur_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $vacc_sale_ret_list = $this->ledger_report->get_vacc_sale_return_expenses_details($get);  
      if(!empty($vacc_sale_ret_list['expense_list'])) 
           {   foreach ($vacc_sale_ret_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $opd_refund_list = $this->ledger_report->get_opd_refund_expenses_details($get); 
       if(!empty($opd_refund_list['expense_list'])) 
           {   foreach ($opd_refund_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $path_refund_list = $this->ledger_report->get_path_refund_expenses_details($get); 
       if(!empty($path_refund_list['expense_list']))  
           {   foreach ($path_refund_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $med_refund_list = $this->ledger_report->get_med_refund_expenses_details($get); 
      if(!empty($med_refund_list['expense_list']))  
           {   foreach ($med_refund_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $ipd_refund_list= $this->ledger_report->get_ipd_refund_expenses_details($get);  
       if(!empty($ipd_refund_list['expense_list'])) 
           {   foreach ($ipd_refund_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $ot_refund_list= $this->ledger_report->get_ot_refund_expenses_details($get);  
       if(!empty($ot_refund_list['expense_list']))  
           {   foreach ($ot_refund_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $bb_refund_list = $this->ledger_report->get_bb_refund_expenses_details($get); 
       if(!empty($bb_refund_list['expense_list']))  
           {   foreach ($bb_refund_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $ambulance_list = $this->ledger_report->get_ambulance_expenses_details($get); 
       if(!empty($ambulance_list['expense_list']))  
           {   foreach ($ambulance_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
      $opd_bill_list = $this->ledger_report->get_opd_bill_refund_expenses_details($get);  
       if(!empty($opd_bill_list['expense_list'])) 
           {   foreach ($opd_bill_list['expense_list'] as $key => $value) { 
                    $datas[] = $value;  
                } 
           }  
      $daycare_list = $this->ledger_report->get_daycare_expenses_details($get); 
       if(!empty($daycare_list['expense_list']))  
           {   foreach ($daycare_list['expense_list'] as $key => $value) {  
                    $datas[] = $value;  
                } 
           }  
        foreach ($datas as $key => $row)  
        { 
            $price[$key] = $row->booking_date;  
        } 
        array_multisort($price, SORT_DESC, $datas); 
     }  
   //echo "<pre>"; print_r($datas);die(); 
     $data['report_list'] = $datas; 
     $data['get'] = $get; 
     $data['page_title'] ="Ledger Reports";   
     $this->load->view('reports/ledger_reports_list',$data);  
  } 
  
  
  // collection with time
    public function collections_with_time(){
       unauthorise_permission('42','245');
       $this->load->model('general/general_model','general_model');
       $data['employee_list'] = $this->general_model->branch_user_list(); 
       $data['department'] = get_collecton_department(); 
       $data['page_title'] = 'Collections Reports'; 
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('reports/collection_time_report',$data);
    }
    
        
    public function print_opd_collection_with_time_reports()  
    { 
        
      $this->load->model('reports/advance_reports_model','advance_reports');
      unauthorise_permission('42','245'); 
      $get = $this->input->get(); 
       $departs = array();  
      if(!empty($get['dept']) && $get['dept'] !='') 
      { 
        $dept = $get['dept']; 
        $departs = explode(',', $dept); 
      }
      $users_data = $this->session->userdata('auth_users'); 
      $data['expense_list'] = $this->advance_reports->get_expenses_details($get);   
      $branch_list= $this->session->userdata('sub_branches_data');  
      $parent_id= $users_data['parent_id']; 
      $branch_ids = array_column($branch_list, 'id');   
      $data['branch_collection_list'] = []; 
     if(in_array(2, $departs) || empty($departs)) 
      {
      $data['self_opd_collection_list'] = $this->advance_reports->self_opd_collection_list($get);
      } 
      if(in_array(14, $departs) || empty($departs)) 
      {
      $data['self_day_care_collection_list'] = $this->advance_reports->self_day_care_collection_list($get); 
      }
     // print_r($data['self_day_care_collection_list']);die();  
      /* 14-10-2019 */
      if(in_array(3, $departs) || empty($departs))  
      {
      $data['self_purchase_return_collection_list'] = $this->advance_reports->self_purchase_collection_list($get);
      } 
     if(in_array(6, $departs) || empty($departs)){  
         $data['self_invent_return_collection_list'] = $this->advance_reports->self_inven_pur_ret_collection_list($get);  
      } 

      /* 14-10-2019 */  
       /* 06-11-2019 */
     if(in_array(13, $departs) || empty($departs))  
      {
      $data['self_ambulance_collection_list'] = $this->advance_reports->self_ambulance_collection_list($get); 
      }
        /* 06-11-2019 */  
    if(in_array(4, $departs) || empty($departs))  
      {
      $data['self_billing_collection_list'] = $this->advance_reports->self_billing_collection_list($get); 
      }
      
      if(in_array(3, $departs) || empty($departs))  
      {
      $data['self_medicine_collection_list'] = $this->advance_reports->self_medicine_collection_list($get); 
      $data['self_medicine_return_collection_list'] = $this->advance_reports->self_medicine_return_collection_list($get);
      } 
      if(in_array(5, $departs) || empty($departs))  
      {
      $data['self_ipd_collection_list'] = $this->advance_reports->self_ipd_collection_list($get);
      } 
       if(in_array(1, $departs) || empty($departs)) 
      {
      $data['pathology_doctor_collection_list'] = $this->advance_reports->pathology_doctor_collection_list($get); 
      $data['pathology_self_collection_list'] = $this->advance_reports->pathology_self_collection_list($get); 
      } 
     if(in_array(7, $departs) || empty($departs)) 
     {
      $data['self_vaccination_collection_list'] = $this->advance_reports->self_vaccination_collection_list($get); 
     }  
     if(in_array(8, $departs) || empty($departs)) 
     {
      $data['self_ot_collection_list'] = $this->advance_reports->self_ot_collection_list($get); 
     }  
    if(in_array(10, $departs) || empty($departs)) 
    {
      $data['self_blood_bank_collection_list'] = $this->advance_reports->self_blood_bank_collection_list($get);
    }
           // print_r($data['self_ot_collection_list']); die; 
      if(!empty($branch_ids)) 
      { 
        if(in_array(2, $departs) || empty($departs))  
        {
        $data['branch_collection_list'] = $this->advance_reports->branch_opd_collection_list($get,$branch_ids); 
        } 
       if(in_array(14, $departs) || empty($departs))  
       {
        $data['branch_day_care_collection_list'] = $this->advance_reports->branch_day_care_collection_list($get,$branch_ids);
       }  
       if(in_array(13, $departs) || empty($departs))  
       {
        /* 06-11-2019 */  
        $data['branch_ambulance_collection_list'] = $this->advance_reports->branch_ambulance_collection_list($get,$branch_ids); 
        /* 06-11-2019 */  
       }  
       if(in_array(4, $departs) || empty($departs)) 
       {
        $data['branch_billing_collection_list'] = $this->advance_reports->branch_billing_collection_list($get,$branch_ids); 
       }  
       if(in_array(3, $departs) || empty($departs)) 
       {
        $data['branch_medicine_collection_list'] = $this->advance_reports->branch_medicine_collection_list($get,$branch_ids); 
        $data['branch_medicine_return_collection_list'] = $this->advance_reports->branch_medicine_return_collection_list($get,$branch_ids); 
       }  
       if(in_array(7, $departs) || empty($departs)) 
       {
        $data['branch_vaccination_collection_list'] = $this->advance_reports->branch_vaccination_collection_list($get,$branch_ids); 
        //print_r($data['branch_vaccination_collection_list']); die;
       }
       if(in_array(5, $departs) || empty($departs)) 
       {
        $data['branch_ipd_collection_list'] = $this->advance_reports->branch_ipd_collection_list($get,$branch_ids); 
       }  
       if(in_array(1, $departs) || empty($departs)) 
       {
        $data['pathology_branch_collection_list'] = $this->advance_reports->pathology_branch_collection_list($get,$branch_ids);
       }  
       if(in_array(8, $departs) || empty($departs)) 
       {  
        $data['branch_ot_collection_list'] = $this->advance_reports->branch_ot_collection_list($get,$branch_ids); 
       }  
       if(in_array(10, $departs) || empty($departs))  
        {
        /* blood bank collection report */  
        $data['blood_bank_branch_collection_list'] = $this->advance_reports->blood_bank_branch_collection_list($get,$branch_ids); 
        /* branch blood bank */ 
        }
          
      } 
      $data['get'] = $get;  
      $data['collection_tab_setting'] = $this->advance_reports->get_collection_tab_setting(); 
      //$this->load->view('reports/list_collection_report_data',$data);   
        
      if(!empty($get['send']) && $get['send']=='preview') 
      { 
        $this->load->view('reports/list_collection_time_report_preview',$data); 
      } 
      else  
      { 
			$from_date = $get['start_date'];  
			$to_date = $get['end_date'];  

			$middle_replace_part=$this->load->view('reports/list_collection_time_report_data',$data,true);  
			$this->load->library('m_pdf');  
			$this->m_pdf->pdf->setAutoTopMargin = 'stretch';  
			$this->m_pdf->pdf->SetHeader('  
			<div style="text-align: center; font-weight: bold;">  
			Collection Report from '.$from_date.' To '.$to_date.' 
			</div>'); 
			$this->m_pdf->pdf->setAutoBottomMargin = 'stretch'; 
			$this->m_pdf->pdf->SetFooter('<div style="width:100%;  height:50px; border-top:solid 1px #000; text-align:center; font-weight:bold;">{PAGENO}/{nbpg}</div>');   
			$pdfFilePath = 'collection_report.pdf';   
			$this->m_pdf->pdf->WriteHTML($middle_replace_part,2); 
			//download it.  
			$this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf 
    }
        
    } 
 // doctor wise collection  
    public function doctor_wise_collection_report(){  
       unauthorise_permission('42','245');  
      $data['page_title'] = 'Doctor Wise Collections Reports';  
      $data['department'] = get_collecton_department(); 
       $data['from_c_date'] = date('d-m-Y');  
       $data['to_c_date'] = date('d-m-Y');  
       $this->load->view('reports/doctor_collection_report',$data); 
    } 
 public function print_doctor_collection_reports()  
    { 
      $this->load->model('reports/reports_doctor_wise_model','reports_doctor'); 
       $get = $this->input->get();  
       if(!empty($get)) 
       {  
          $data['page_title'] = 'Doctor Wise Collections Reports';  
          $data['list'] = $this->reports_doctor->doctor_commission_details($get); 
          $data['doctor_list'] = $this->reports_doctor->doctor_list_details($get['doc_id'],$get['dept']);
          //echo "<pre>"; print_r($data['doctor_list']);die();  
          $data['get']=$get;  
        $this->load->view('reports/doctor_wise_collection_reports',$data);  
       }  
    } 
      
// module wise profit loss  
     public function modul_profit_loss_report() 
    { 
        unauthorise_permission('43','252'); 
        $this->load->model('general/general_model');  
        $data['expense_category_list'] = $this->general_model->expense_category_list(); 
        $data['dept_list']=get_expense_department();  
        $data['page_title'] = 'Module Profit/Loss Reports'; 
        $this->load->view('reports/modul_profit_loss',$data); 
    } 
    public function module_profit_loss_reports()  
    {   
      unauthorise_permission('42','245'); 
      $get = $this->input->get(); 
      $users_data = $this->session->userdata('auth_users'); 
      $get['branch_id']=$users_data['parent_id']; 
      $data['normal_expense_list'] = $this->reports->get_exp_expenses_details($get);  
      $data['doc_com_list'] = $this->reports->get_exp_doctor_commission($get);  
      $data['salary_list'] = $this->reports->get_salary_expenses_details($get); 
      $data['med_pur_list'] = $this->reports->get_medicine_expenses_details($get);  
      $data['sale_ret_list'] = $this->reports->get_sale_return_expenses_details($get);  
      $data['stock_list'] = $this->reports->get_stock_inventory_expenses_details($get); 
      $data['vacc_pur_list'] = $this->reports->get_vacc_purchase_expenses_details($get);  
      $data['vacc_sale_ret_list'] = $this->reports->get_vacc_sale_return_expenses_details($get);  
      $data['opd_refund_list'] = $this->reports->get_opd_refund_expenses_details($get); 
      $data['path_refund_list'] = $this->reports->get_path_refund_expenses_details($get); 
      $data['med_refund_list'] = $this->reports->get_med_refund_expenses_details($get); 
      $data['ipd_refund_list'] = $this->reports->get_ipd_refund_expenses_details($get); 
      $data['ot_refund_list'] = $this->reports->get_ot_refund_expenses_details($get); 
      $data['bb_refund_list'] = $this->reports->get_bb_refund_expenses_details($get); 
      $data['ambulance_list'] = $this->reports->get_ambulance_expenses_details($get); 
      $data['opd_bill_list'] = $this->reports->get_opd_bill_refund_expenses_details($get);  
      $data['daycare_list'] = $this->reports->get_daycare_expenses_details($get); 
     // echo "<pre>";print_r($data);die();  
      //$data['all_expense_list'] = $this->reports->get_expenses_details($get);   
      $branch_list= $this->session->userdata('sub_branches_data');  
      $parent_id= $users_data['parent_id']; 
      $branch_ids = array_column($branch_list, 'id');   
      $data['branch_collection_list'] = []; 
      //$data['doctor_collection_list'] = $this->reports->doctor_collection_list($get); 
      $data['self_opd_collection_list'] = $this->reports->self_opd_collection_list($get); 
      $data['self_day_care_collection_list'] = $this->reports->self_day_care_collection_list($get); 
      /* 14-10-2019 */  
      $data['self_purchase_return_collection_list'] = $this->reports->self_purchase_collection_list($get);  
      $data['self_ambulance_collection_list'] = $this->reports->self_ambulance_collection_list($get); 
        /* 06-11-2019 */  
      $data['self_billing_collection_list'] = $this->reports->self_billing_collection_list($get); 
      $data['self_medicine_collection_list'] = $this->reports->self_medicine_collection_list($get); 
      $data['self_medicine_return_collection_list'] = $this->reports->self_medicine_return_collection_list($get); 
      $data['self_ipd_collection_list'] = $this->reports->self_ipd_collection_list($get); 
      $data['pathology_doctor_collection_list'] = $this->reports->pathology_doctor_collection_list($get); 
      $data['pathology_self_collection_list'] = $this->reports->pathology_self_collection_list($get); 
      $data['self_vaccination_collection_list'] = $this->reports->self_vaccination_collection_list($get); 
      //print_r($data['branch_vaccination_collection_list']); die;  
      $data['self_ot_collection_list'] = $this->reports->self_ot_collection_list($get); 
      $data['self_blood_bank_collection_list'] = $this->reports->self_blood_bank_collection_list($get); 
           // print_r($data['self_ot_collection_list']); die; 
       $data['self_inven_pur_ret_collection_list'] = $this->reports->self_inven_pur_ret_collection_list($get);  
       $data['self_vaccination_pur_ret_collection_list'] = $this->reports->self_vaccination_pur_ret_collection_list($get);  
      // echo "<pre>";print_r($data['self_vaccination_pur_ret_collection_list']); die;  
      if(!empty($branch_ids)) 
      { 
        $data['branch_collection_list'] = $this->reports->branch_opd_collection_list($get,$branch_ids); 
        $data['branch_day_care_collection_list'] = $this->reports->branch_day_care_collection_list($get,$branch_ids); 
        /* 06-11-2019 */  
        $data['branch_ambulance_collection_list'] = $this->reports->branch_ambulance_collection_list($get,$branch_ids); 
        /* 06-11-2019 */  
        $data['branch_billing_collection_list'] = $this->reports->branch_billing_collection_list($get,$branch_ids); 
        $data['branch_medicine_collection_list'] = $this->reports->branch_medicine_collection_list($get,$branch_ids); 
        $data['branch_medicine_return_collection_list'] = $this->reports->branch_medicine_return_collection_list($get,$branch_ids); 
          
        $data['branch_vaccination_collection_list'] = $this->reports->branch_vaccination_collection_list($get,$branch_ids); 
        //print_r($data['branch_vaccination_collection_list']); die;  
        $data['branch_ipd_collection_list'] = $this->reports->branch_ipd_collection_list($get,$branch_ids); 
        $data['pathology_branch_collection_list'] = $this->reports->pathology_branch_collection_list($get,$branch_ids); 
        $data['branch_ot_collection_list'] = $this->reports->branch_ot_collection_list($get,$branch_ids); 
        /* blood bank collection report */  
        $data['blood_bank_branch_collection_list'] = $this->reports->blood_bank_branch_collection_list($get,$branch_ids); 
        /* branch blood bank */ 
          
      } 
      $data['get'] = $get;  
      $data['collection_tab_setting'] = $this->reports->get_collection_tab_setting(); 
   // $from_date = $get['start_date'];  
   // $to_date = $get['end_date'];  
  $this->load->view('reports/profit_loss_module',$data);  
   } 
   
   
    public function discount_report_excel()
    {
          $this->load->model('reports/reports_model','reports');
          $get = $this->input->get();
          $departs = array(); 
          if(!empty($get['dept']) && $get['dept'] !='') 
          { 
            $dept = $get['dept']; 
            $departs = explode(',', $dept); 
          }
          $users_data = $this->session->userdata('auth_users');
          //$data['expense_list'] = $this->reports->get_expenses_details($get); 
          $branch_list= $this->session->userdata('sub_branches_data');
          $parent_id= $users_data['parent_id'];
          $branch_ids = array_column($branch_list, 'id'); 
           $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          
          
          
          $fields_array = array('S. No.','Patient Name','Patient Category','Authorize Person','Patient Reg. No.','Booking Code','Date','Total Amount','Discount','Net Amount','Paid Amount','Balance');
          
            $fields = $fields_array;
          
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          } 
        $self_opd_coll = $this->reports->self_opd_collection_list($get);
        //echo "<pre>"; print_r($self_opd_coll); exit;
        $self_opd_coll_data='';
        $self_opd_coll_payment_mode='';
        //echo "<pre>"; print_r($self_opd_coll); exit;
        if(in_array(2, $departs) || empty($departs))  
        {
          $self_opd_coll_data =  $self_opd_coll['self_opd_coll'];
          $self_opd_coll_payment_mode = $self_opd_coll['self_opd_coll_payment_mode'];
        }
       
        $data_excel= array();
        
          if(!empty($self_opd_coll_data))
          {
                $i=0;
               $rowData = array();
               $k = 1;
               $opd_k=1;
               $self_opd_coll_total =0;
               
               if($opd_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'OPD');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
               
               
               foreach($self_opd_coll_data as $collection)
               {
                   
                       
                    if($collection->balance=='1.00' || $collection->balance=='0.00'){
                    
                    $bal= '0.00';
                    }else{ 
                    
                    $bal = $collection->balance-1;
                    }
                    array_push($rowData,$opd_k);
                    array_push($rowData,$collection->patient_name);
                    array_push($rowData,$collection->patient_category_name);
                    array_push($rowData,$collection->authorize_person_name);
                    array_push($rowData,$collection->patient_code);
                    array_push($rowData,$collection->booking_code);
                    array_push($rowData,date('d-m-Y', strtotime($collection->created_date)));
                    array_push($rowData,$collection->total_amount);
                    array_push($rowData,$collection->discount_amount);
                    array_push($rowData,$collection->net_amount);
                    array_push($rowData,$collection->debit);
                    array_push($rowData,$bal);
                                  
                   $self_opd_coll_total = $self_opd_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                $opd_k++;
               }
                    if(!empty($self_opd_coll_total))
                    {
               		
                   		array_push($rowData,'Total');
                        array_push($rowData,number_format($self_opd_coll_total,2));
                    

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
             
          }
          
          /////opd billing/////
            
            
            
            
            
      if(in_array(4, $departs) || empty($departs))  
      {
        $self_billing_collection_list = $this->reports->self_billing_collection_list($get);
        $self_bill_coll_data =  $self_billing_collection_list['self_bill_coll'];
        $self_bill_coll_payment_mode = $self_billing_collection_list['self_bill_coll_payment_mode'];
      }
        
          if(!empty($self_bill_coll_data))
          {
               $rowData = array();
              // $k = 1;
               $opd_billing_k=1;
               $self_billing_coll_total =0;
               
               if($opd_billing_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'OPD Billing');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
               
               foreach($self_bill_coll_data as $collection)
               {
                  
                  if($collection->balance=='1.00' || $collection->balance=='0.00'){
                               
                                $bal = '0.00';
                            }else{ 
                                 
                               $bal = $collection->balance-1;
                            }
                  array_push($rowData,$opd_billing_k,trim($collection->patient_name),$collection->patient_category_name,$collection->authorize_person_name,$collection->patient_code,$collection->booking_code,date('d-m-Y', strtotime($collection->created_date)),$collection->total_amount,$collection->discount_amount,$collection->net_amount,$collection->debit,$bal);
                   
                       
                       
                  
                   $self_billing_coll_total = $self_billing_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                //$k++;
                 
               
                   $opd_billing_k++;
               }

               
               if(!empty($self_billing_coll_total))
                {
                    array_push($rowData,'Total');
                    array_push($rowData,number_format($self_billing_coll_total,2));
                
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
          
          }
          //opd billing end ///
          
          /////////////////////////////////pathology/////////////////////////
          if(in_array(1, $departs) || empty($departs))  
          {
          $self_path_collection_list = $this->reports->pathology_self_collection_list($get);
         
            $self_path_coll_data = $self_path_collection_list['path_coll'];
            $self_path_coll_payment_mode = $self_path_collection_list['path_coll_pay_mode'];
          }
           //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_path_coll_data))
          {
              $path_k=1;
              
              if($path_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'Pathology');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
          	   foreach($self_path_coll_data as $collection)
               {
                    if($collection->balance=='1.00' || $collection->balance=='0.00'){
                    
                    $bal = '0.00';
                    }else{ 
                    
                    $bal = $collection->balance-1;
                    }
                  array_push($rowData,$path_k,trim($collection->patient_name),$collection->patient_category_name,$collection->authorize_person_name,$collection->patient_code,$collection->booking_code,date('d-m-Y', strtotime($collection->created_date)),$collection->total_amount,$collection->discount_amount,$collection->net_amount,$collection->debit,$bal);
                  
                   $self_path_coll_total = $self_path_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                 
               
                   $path_k++;
               }


              
              if(!empty($self_path_coll_total))
                {
               		
                   		array_push($rowData,'Total');
                        array_push($rowData,number_format($self_path_coll_total,2));
                               
                                
                                

                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          

          if(in_array(3, $departs) || empty($departs)) 
          {
            $self_sale_med_collection_list = $this->reports->self_medicine_collection_list($get);
         
            $self_sale_med_coll_data = $self_sale_med_collection_list['med_coll'];
            $self_sale_med_coll_payment_mode = $self_sale_med_collection_list['med_coll_pay_mode'];
           } //echo "<pre>"; print_r($self_ipd_coll_payment_mode); exit;
          if(!empty($self_sale_med_coll_data))
          {
              $med_k=1;
              
              if($med_k=='1')
               {
               		//$i=$k;
                   array_push($rowData,'Medicine');
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i = $i+1;
               }
          	   foreach($self_sale_med_coll_data as $collection)
               {
                   if($collection->balance=='1.00' || $collection->balance=='0.00'){
                    
                    $bal = '0.00';
                    }else{ 
                    
                    $bal = $collection->balance-1;
                    }
                  array_push($rowData,$med_k,trim($collection->patient_name),$collection->patient_category_name,$collection->authorize_person_name,$collection->patient_code,$collection->booking_code,date('d-m-Y', strtotime($collection->created_date)),$collection->total_amount,$collection->discount_amount,$collection->net_amount,$collection->debit,$bal);
                  
                   $self_sale_med_coll_total = $self_sale_med_coll_total+$collection->debit;
                   
                   $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;  
                $k++;
                $med_k++;
                 
               }


               if(!empty($self_sale_med_coll_total))
               {
                   	
                    array_push($rowData,'Total');   		  
                    array_push($rowData,$self_sale_med_coll_total);
            
                              
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data_excel[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    
                    $i++;
                       
                   
               }
               
               



          }
          
           /////////////////////////////////Dialysis/////////////////////////
          
         //echo "<pre>"; print_r($fields); exit;
          // Fetching the table data
         $rowp = 2;
          if(!empty($data_excel))
          {
               foreach($data_excel as $boking_data)
               {
                    $colp = 0;
                    $row_vals=1;
                    foreach ($fields as $fieldd)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colp, $rowp, $boking_data[$fieldd]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_vals)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_vals)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $colp++;
                         $row_vals++;
                    }
                    $rowp++;
               }
               
               
            
               
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=authorizeperson_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data_excel))
         {
             if (ob_get_length() > 0 ) {
                    ob_end_clean();
             }
          $objWriter->save('php://output');
         }
    
    }

  
}
?>