<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_visiting_doctor extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_visiting_doctor/ipd_visiting_doctor_model','ipd_visiting');
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
        // End Defaul Search
        $data['form_data'] = array('from_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Visiting Doctor Report';
        $this->load->model('general/general_model'); 
        $data['doctor_list'] = $this->general_model->doctors_list();
        
        $this->load->view('ipd_visiting_doctor/list',$data);
    }
    
    public function ajax_list()
    {  
        
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->ipd_visiting->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        $grand_total_discount=0;
        //$grand_net_amount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0;
        foreach ($list as $reports) 
        {
         
               $grand_paid_amount = $grand_paid_amount + $reports->net_price;
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
            $row[] = $reports->ipd_no;  
            $row[] = date('d-m-Y',strtotime($reports->admission_date));  
            $row[] = $reports->patient_name;  
            $row[] = $reports->doctor_name;  
            $row[] = $reports->net_price;
            $row[] = date('d-m-Y',strtotime($reports->start_date));  
            
            $data[] = $row;
            $tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = '';  
              $tot_row[] = '';
              $tot_row[] = '';  
              $tot_row[] = '';  
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:right;" value='.number_format($grand_paid_amount,2,'.','').' readonly >'; 
              $tot_row[] = '';
              
              $data[] = $tot_row; 
           }

            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_visiting->count_all(),
                        "recordsFiltered" => $this->ipd_visiting->count_filtered(),
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
                        'from_date'=>$post['from_date'],
                        'branch_id'=>$post['branch_id'],
                        'referral_doctor'=>'',
                        'patient_code'=>'',
                        'patient_name'=>'',
                        'mobile_no'=>'',
                        'end_date'=>$post['end_date'],
                        'attended_doctor'=>'',
                        'employee'=>$post['employee'],
                      );
         $this->session->set_userdata('ipd_visiting_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('ipd_visiting_search_data');
    }

    public function ipd_report_excel()
    {
        unauthorise_permission('126','768');
        
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          
          $fields = array('IPD No.','Admission Date','Patient Name','Doctor Name','Amount','Date');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
              $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
              $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
               $row_heading++;
          }
          $list = $this->ipd_visiting->search_report_data();
          //echo "<pre>"; print_r($list); die;
          $rowData = array();
          $data= array();
          $ttl_paid=0;
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                   
                   
                    $ttl_paid=$ttl_paid + $reports->net_price;  // added on 02-feb-2018
                    
                    array_push($rowData,$reports->ipd_no,date('d-m-Y',strtotime($reports->admission_date)) ,$reports->patient_name,$reports->doctor_name,$reports->net_price,date('d-m-Y',strtotime($reports->start_date)));
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
                $objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,'Total');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,$ttl_paid);
              // added on 02-Feb-2018
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream; charset=UTF-8');
        header("Content-Disposition: attachment; filename=ipd_visiting_report_".time().".xls"); 
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
        $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();        
        $post = $this->input->post(); 
        
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('ipd_visiting_search_data',$post); 
        }
        //$data['referal_doctor_list'] = $this->ipd_booking->referal_doctor_list();
        $data['attended_doctor_list'] = $this->ipd_booking->attended_doctor_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        
        $data['search_data'] = $this->session->userdata('ipd_visiting_search_data');
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
          
           $data['form_data'] = array(
                                   'from_date'=>$search_data['from_date'],
                                   'end_date'=>$search_data['end_date'],
                                   'end_date'=>$search_data['end_date'],
                                   'doctor_id'=>$search_data['doctor_id'],

                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'from_date'=>'',
                                   'end_date'=>'',
                                   'doctor_id'=>'',
                                   
                                 );
        }  
        
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('ipd_visiting_search_data', $marge_post);

        }
        $opd_search = $this->session->userdata('ipd_visiting_search_data');
        //echo "<pre>"; print_r($opd_search); die;
        
        //$this->load->view('ipd_collection/advance_search',$data);
    }

    public function pdf_ipd_report()
    {   
        unauthorise_permission('126','770');
        $data['print_status']="";
        $data['data_list'] = $this->ipd_visiting->search_report_data();
        $this->load->view('ipd_visiting_doctor/ipd_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("doctor_visiting_report_".time().".pdf");
    }

    public function print_ipd_report()
    {   
        unauthorise_permission('126','771');
        $data['print_status']="1";
        $data['data_list'] = $this->ipd_visiting->search_report_data();
        $this->load->view('ipd_visiting_doctor/ipd_report_html',$data); 
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
    public function print_ipd_collection_reports()
    { 
     unauthorise_permission('126','771');
     $get = $this->input->get();
     $data['ipd_collection_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['ipd_collection_list'] = $this->ipd_collection_reports->get_ipd_collection_list_details($get);
     } 
     $this->load->view('ipd_collection/list_ipd_collection',$data);  

    }

    public function print_discharge_receipt($ipd_id="",$patient_id='',$pay_id='',$date='')
    {
        $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Discharge Payment";
        if(!empty($ipd_id))
        {
            $ipd_id= $ipd_id;
        }
        else
        {
          $ipd_id= $this->session->userdata('ipd_id');
        }
        
        $get_by_id_data = $this->ipd_collection_reports->get_all_detail_print($pay_id,5);
        $get_balance_previous= $this->ipd_collection_reports->get_balance_previous($pay_id,$patient_id,$date,5);
        // echo "<pre>"; print_r($get_by_id_data); exit;
        $template_format = $this->ipd_collection_reports->template_format(array('section_id'=>5,'types'=>1));  
        $data['type']=5;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['user_detail']=$user_detail;
        $data['new_balance']=$get_balance_previous;
        $this->load->view('ipd_collection/print_discharge_bill',$data);
 }

    public function download_image($ipd_id="",$patient_id='',$pay_id='',$date='')
    {
      
      $data['type'] = 5;
      $data['download_type'] = '2'; //for image
      $user_detail= $this->session->userdata('auth_users');
        $data['page_title'] = "Discharge Payment";
        if(!empty($ipd_id))
        {
            $ipd_id= $ipd_id;
        }
        else
        {
          $ipd_id= $this->session->userdata('ipd_id');
        }
        
        $get_by_id_data = $this->ipd_collection_reports->get_all_detail_print($pay_id,5);
        $get_balance_previous= $this->ipd_collection_reports->get_balance_previous($pay_id,$patient_id,$date,5);

       // echo "<pre>"; print_r($get_by_id_data); exit;
        $template_format = $this->ipd_collection_reports->template_format(array('section_id'=>5,'types'=>1));  
        $data['type']=5;
        $data['template_data']=$template_format;
        $data['all_detail']= $get_by_id_data;
        $data['user_detail']=$user_detail;
        $data['new_balance']=$get_balance_previous;
      $this->load->view('ipd_collection/print_discharge_bill',$data);
    }

    
     
}
?>