<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_summary_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('summary_reports/opd_summary_report_model','opd_collection_report');
        $this->load->library('form_validation');

    }

    public function index()
    {   
        $users_data = $this->session->userdata('auth_users'); 
        $this->session->unset_userdata('opd_summary_search');
        $search_date = $this->session->userdata('opd_summary_search');
        // Default Search Setting
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
        // End Defaul Search
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date,'attended_doctor'=>'','branch_id'=>$users_data['parent_id']);
        $data['page_title'] = 'OPD Summary Report list';
        
        $this->load->view('opd_summary/list',$data);

    }
    
    public function ajax_list()
    {  
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->opd_collection_report->get_datatables();
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
                    $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
               } 
                                 
            $no++;
            $row = array(); 
            //$row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$reports->id.'">'.$check_script; 
            $row[] = $i;  
            //$row[] = date('d-m-Y',strtotime($reports->booking_date));
            $row[] = $reports->booking_code;  
            $row[] = $reports->token_no;
            $row[] = $reports->patient_name;
            $row[] = $reports->patient_code;
            //$row[] = 'Dr. '.$reports->doctor_name;
            $row[] = $reports->debit;  
            //$row[] = $reports->particular_name;
            $row[] = $reports->mode;
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
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >';  
              
              $tot_row[]='';
              $tot_row[] = '';
              $data[] = $tot_row; 
           }
           $i++;
        }
        
        $output = array(
                      "draw" => $_POST['draw'],
                      "recordsTotal" => $this->opd_collection_report->count_all(),
                      "recordsFiltered" => $this->opd_collection_report->count_filtered(),
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
                                   'attended_doctor'=>$post['attended_doctor'],
                                 );
         $this->session->set_userdata('opd_summary_search',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('opd_summary_search');
    }

    public function opd_summary_report_excel()
    {
        
         
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          // Field names in the first row
          $fields = array('S. No.','Receipt No.','Token No.','Patient Name', 'Reg. No.','Amount','Payment Mode');
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
          
          $data_list = $this->opd_collection_report->search_report_data();
          $list = $data_list['self_opd_coll'];
          
         

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               $m=1;
               foreach($list as $reports)
               {
                  
                    $ttl_paid= $ttl_paid + $reports->debit;
                    
                    array_push($rowData,$m,$reports->booking_code,$reports->token_no,$reports->patient_name,$reports->patient_code,$reports->debit,$reports->mode);
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
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':F'.$row.'')->getFont()->setBold( true );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,'Total');
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,$ttl_paid);
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
         header("Content-Disposition: attachment; filename=opd_summary_report_".time().".xls");   
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
        $this->load->model('opd/opd_model','opd');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('opd_summary_search',$post); 
        }
        $data['referal_doctor_list'] = $this->opd->referal_doctor_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->general_model->branch_user_list();        
        //$data['employee_list'] = $this->opd->employee_list();

$data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();

        $data['profile_list'] = $this->opd->profile_list();
        $data['opd_collection_resport_search_data'] = $this->session->userdata('opd_summary_search');
        if(isset($data['opd_collection_resport_search_data']) && !empty($data['opd_collection_resport_search_data']))
        {
           $opd_collection_resport_search_data = $data['opd_collection_resport_search_data'];
           if(isset($opd_collection_resport_search_data['start_date']) && !empty($opd_collection_resport_search_data['start_date']))
           {
            $start_date=$opd_collection_resport_search_data['start_date'];
           }
           else
           {
            $start_date='';
           }
            if(isset($opd_collection_resport_search_data['patient_code']) && !empty($opd_collection_resport_search_data['patient_code']))
           {
            $patient_code=$opd_collection_resport_search_data['patient_code'];
           }
           else
           {
            $patient_code='';
           }
            if(isset($opd_collection_resport_search_data['patient_name']) && !empty($opd_collection_resport_search_data['patient_name']))
           {
            $patient_name=$opd_collection_resport_search_data['patient_name'];
           }
           else
           {
            $patient_name='';
           }
            if(isset($opd_collection_resport_search_data['mobile_no']) && !empty($opd_collection_resport_search_data['mobile_no']))
           {
            $mobile_no=$opd_collection_resport_search_data['mobile_no'];
           }
           else
           {
            $mobile_no='';
           }
            if(isset($opd_collection_resport_search_data['end_date']) && !empty($opd_collection_resport_search_data['end_date']))
           {
            $end_date=$opd_collection_resport_search_data['end_date'];
           }
           else
           {
            $end_date='';
           }
            if(isset($opd_collection_resport_search_data['attended_doctor']) && !empty($opd_collection_resport_search_data['attended_doctor']))
           {
            $attended_doctor=$opd_collection_resport_search_data['attended_doctor'];
           }
           else
           {
            $attended_doctor='';
           }
            if(isset($opd_collection_resport_search_data['particular']) && !empty($opd_collection_resport_search_data['particular']))
           {
            $particular=$opd_collection_resport_search_data['particular'];
           }
           else
           {
             $particular='';
           }
            if(isset($opd_collection_resport_search_data['employee']) && !empty($opd_collection_resport_search_data['employee']))
           {
            $employee=$opd_collection_resport_search_data['employee'];
           }
           else
           {
             $employee='';
           }

if(isset($opd_collection_resport_search_data['referred_by']) && !empty($opd_collection_resport_search_data['referred_by']))
           {
              $referred_by = $opd_collection_resport_search_data['referred_by'];
           }
           else
           {
             $referred_by='';
           }
            if(isset($opd_collection_resport_search_data['referral_hospital']) && !empty($opd_collection_resport_search_data['referral_hospital']))
           {
              $referral_hospital = $opd_collection_resport_search_data['referral_hospital'];
           }
           else
           {
             $referral_hospital='';
           }
            if(isset($opd_collection_resport_search_data['refered_id']) && !empty($opd_collection_resport_search_data['refered_id']))
           {
              $refered_id = $opd_collection_resport_search_data['refered_id'];
           }
           else
           {
            $refered_id='';
           }
           
            if(isset($opd_collection_resport_search_data['particulars']) && !empty($opd_collection_resport_search_data['particulars']))
           {
            $particulars=$opd_collection_resport_search_data['particulars'];
           }
           
           else
           {
            $particulars='';
          }
           $data['form_data'] = array(
                                   'start_date'=>$start_date,
                                   'patient_code'=>$patient_code,
                                   'patient_name'=>$patient_name,
                                   'mobile_no'=>$mobile_no,
                                   'end_date'=> $end_date,
                                   'attended_doctor'=>$attended_doctor,
                                   'particular'=>$particular,
                                   'employee'=>$employee,
                                   'particulars'=>$particulars,
'referred_by'=>$referred_by,
                                   'referral_hospital'=>$referral_hospital,
                                   'refered_id'=>$refered_id,
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'start_date'=>date('d-m-Y'),
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>date('d-m-Y'),
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'particular'=>'',
                                   'employee'=>'',
                                   'particulars'=>'',
"referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   
                                 );
        }  
        $this->load->view('opd_summary/advance_search',$data);
    }

    public function pdf_opd_summary_report()
    {  
        unauthorise_permission('88','568');  
        $data['print_status']="";
        $data_list = $this->opd_collection_report->search_report_data();
        
         

        $data['data_list'] = $data_list['self_opd_coll'];
        $data['self_opd_coll_payment_mode'] = $data_list['self_opd_coll_payment_mode'];
        $this->load->view('opd_summary/opd_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("opd_summary_report_report_".time().".pdf");
    }

    public function print_opd_collection_report()
    {    
        $data['print_status']="1";
        //$data['data_list'] = $this->opd_collection_report->search_report_data();
        $data_list = $this->opd_collection_report->search_report_data();
        
         

        $data['data_list'] = $data_list['self_opd_coll'];
        $data['self_opd_coll_payment_mode'] = $data_list['self_opd_coll_payment_mode'];
        $this->load->view('opd_summary/opd_report_html',$data); 
    }
    public function get_allsub_branch_list()
    {
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
    public function print_opd_collection_reports()
    { 
      unauthorise_permission('88','569');
     $get = $this->input->get();
     $data['billing_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['billing_collection_list'] = $this->opd_collection_report->get_billing_collection_details($get);
     } 
     $this->load->view('opd_summary/list_opd_collection_report',$data);  

    }

    
     
}
?>