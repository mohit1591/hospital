<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Referral_reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('reports/referral_reports_model','referral_reports');
        $this->load->library('form_validation');
    }


    public function reports(){
       unauthorise_permission('104','648');
       $data['page_title'] = 'Referral Reports';
       $this->load->view('referral_report/referral_report',$data);
    }

     public function get_allsub_branch_list()
     {
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


    public function get_appointment_data($get="")
    {

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 
      $data['appointment_list'] = $this->referral_reports->appointment_report_list($get);

      $data['branch_appointment_list'] = [];
      if(!empty($branch_ids))
      {
        $data['branch_appointment_list'] = $this->referral_reports->branch_referral_list($get,$branch_ids);
      }
      //echo "<pre>"; print_r($data['appointment_list']); exit;
      $data['get'] = $get;
      $this->load->view('referral_report/referral_report_data',$data); 
    }

    public function referral_report_excel($get="")
    {

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 
      $list = $this->referral_reports->appointment_report_list($get);
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

          $objPHPExcel->getActiveSheet()->mergeCells("B1:D1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('B1',$get_branch_detail[0]->branch_name);
          $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setSize(16);

          $objPHPExcel->getActiveSheet()->mergeCells("E1:F1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('E1',$get['start_date'].' To '.$get['end_date']);
          $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setSize(10);


          $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
          $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
          $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
          $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
          $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(20);

      $fields = array('S. No.','Patient Name','Age','Diseases','Mobile No.','Referred By','App. Date','Patient Loc.','Branch','Remarks');
        $col = 0;

      foreach ($fields as $field)
      {
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, $field);
          $col++;
      }

      //print_r($list);die;
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=1;
            $s = 1;
            $date_array = array_unique(array_column($list,'appointment_date'));
            foreach($list as $reports)
            {
              
              if(in_array($reports['appointment_date'],$date_array))
              {
                //$sheet->mergeCells("G:I"); 
                array_push($rowData,date('d/m/y',strtotime($reports['appointment_date'])),'','','','','','','','',''); 
                $used_date = array_search($reports['appointment_date'],$date_array);
                unset($date_array[$used_date]);
                $count = count($rowData); 
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array(); 
                $i++;  
              }
              if($reports['referral_doctor']==0 && $reports['ref_by_other']!=''){
                $reference_name=$reports['ref_by_other'];
              }
              else if($reports['referral_doctor']!='' && $reports['referral_doctor']!=0){
                $reference_name=$reports['doctor_name'];
              }else{
                 $reference_name='';
              }
             array_push($rowData,$s,$reports['patient_name'],$reports['age_y'],$reports['disease_name'],$reports['mobile_no'],$reference_name,date('d/m/y',strtotime($reports['appointment_date'])),$reports['city'],$reports['branch_name'],'');
                $count = count($rowData); 
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++; 
                $s++; 
            }
             
        }
        //print_r($data); exit;
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
        header("Content-Disposition: attachment; filename=referral_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
  


    }

    public function referral_report_csv($get="")
    {

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 
      $list = $this->referral_reports->appointment_report_list($get);
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

          $objPHPExcel->getActiveSheet()->mergeCells("B1:D1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('B1',$get_branch_detail[0]->branch_name);
          $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setSize(16);

          $objPHPExcel->getActiveSheet()->mergeCells("E1:F1");
          $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40); 
          $objPHPExcel->getActiveSheet()->setCellValue('E1',$get['start_date'].' To '.$get['end_date']);
          $objPHPExcel->getActiveSheet()->getStyle("E1")->getFont()->setSize(10);


          $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
          $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
          $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
          $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
          $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
          $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
          $objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(20);

      $fields = array('S. No.','Patient Name','Age','Diseases','Mobile No.','Referred By','App. Date','Patient Loc.','Branch','Remarks');
        $col = 0;

      foreach ($fields as $field)
      {
          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 4, $field);
          $col++;
      }

      //print_r($list);die;
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=1;
            $s = 1;
            $date_array = array_unique(array_column($list,'appointment_date'));
            foreach($list as $reports)
            {
              
              if(in_array($reports['appointment_date'],$date_array))
              {
                //$sheet->mergeCells("G:I"); 
                array_push($rowData,date('d/m/y',strtotime($reports['appointment_date'])),'','','','','','','','',''); 
                $used_date = array_search($reports['appointment_date'],$date_array);
                unset($date_array[$used_date]);
                $count = count($rowData); 
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array(); 
                $i++;  
              }
              if($reports['referral_doctor']==0 && $reports['ref_by_other']!=''){
                $reference_name=$reports['ref_by_other'];
              }
              else if($reports['referral_doctor']!='' && $reports['referral_doctor']!=0){
                $reference_name=$reports['doctor_name'];
              }else{
                 $reference_name='';
              }
             array_push($rowData,$s,$reports['patient_name'],$reports['age_y'],$reports['disease_name'],$reports['mobile_no'],$reference_name,date('d/m/y',strtotime($reports['appointment_date'])),$reports['city'],$reports['branch_name'],'');
                $count = count($rowData); 
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++; 
                $s++; 
            }
             
        }
       // echo "<pre>";print_r($data); exit;
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
        

        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=referral_report_".time().".csv");
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