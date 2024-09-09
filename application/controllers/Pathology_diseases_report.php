<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_diseases_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pathology_diseases_report/pathology_reports_model','reports');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission('147','890');
        $this->session->unset_userdata('search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('search_data');
        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        /*
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {*/
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        //}
        // End Defaul Search
        $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Pathology Diseases Report';
        $this->load->model('general/general_model');
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $data['employee_list'] = $this->general_model->branch_user_list(); 
        $this->load->model('test/test_model');
        $data['diseases_list'] = $this->test_model->diseases_list();
        $this->load->view('pathology_diseases_report/list',$data);
    }
    public function expenses(){
        unauthorise_permission('43','252');
       $data['page_title'] = 'Pathology Expenses Reports';
       $this->load->view('pathology_diseases_report/expense_report',$data);
    }
     public function collections(){
       unauthorise_permission('42','245');
       $data['page_title'] = 'Pathology Collections Reports';
       $this->load->view('pathology_diseases_report/collection_report',$data);
    }
    public function ajax_list()
    {  

        unauthorise_permission('147','890');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->reports->get_datatables();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        
        foreach ($list as $reports) 
        {
               $check_script = "";
               if($i==$total_num){
                    
               }                 
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
            $row[] = $i;
            
            $row[] = $reports->patient_name;  
            $row[] = $reports->patient_code; 
            $row[] = $reports->disease; 
            $row[] = date('d M Y',strtotime($reports->booking_date));
            $row[] = date('d M Y',strtotime($reports->reminder_date));  
            
            $send_reminder = '<a href="javascript:void(0)" class="btn-custom" onclick="send_reminder('.$reports->book_id.','.$reports->patientid.');"><i class="fa fa-envelope-o" aria-hidden="true"></i> SMS</a>';
            $row[] = $send_reminder; 
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
    
    public function send_reminder()
	{
		$data['page_title'] = "Send SMS";
		$this->load->model('general/general_model');
		$post = $this->input->post();

		
		if(isset($post['patient_id']) && !empty($post['patient_id']))
		{
			$patient_data = $this->general_model->patient_list($post['patient_id']);
			$data['patient_id'] = $post['patient_id'];
			$data['booking_id'] = $post['booking_id'];
			$data['name'] = $patient_data[0]->patient_name;
			$data['email'] = $patient_data[0]->patient_email;
			$data['mobile'] = $patient_data[0]->mobile_no;
		}
		
		if(isset($post['person_id']) && !empty($post['person_id']))
		{ 
			//echo "<pre>";print_r($post); exit;
			$mobile_no = $post['mobile'];
			$message = $post['message'];
			send_reminder_sms('','',$mobile_no,'',$message,'',$post['person_id']);
			echo '1'; return false;
		}
	
		$this->load->view('pathology_diseases_report/send_reminder',$data);
	}

    public function search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
              $users_data = $this->session->userdata('auth_users'); 
              $branch_id = $users_data['parent_id'];

              if(isset($post['branch_id']) && !empty($post['branch_id']))
              {
              $branch_id = $post['branch_id'];
              }
              $search_data =  array(
                                   'branch_id'=>$branch_id,
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
                                   'staff_refrenace_id'=>'',
                                   'employee'=>$post['employee'],
                                   'department'=>$post['department'],
                                 );
         $this->session->set_userdata('search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('search_data');
    }

    public function path_report_excel()
    {
      
        unauthorise_permission('147','891');
              $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // added on 31-jan-2018
            $amt_ttl=0;
            $fields = array('S. No.','Patient Name','Reg. No.','Diseases','Booking Date','Reminder Date');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $data_list = $this->reports->search_report_data();
        $list = $data_list['path_coll'];
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            $i=0;
            $m=1;
            foreach($list as $reports)
            {
              // added on 31-jan-2018
                $amt_ttl=$reports->total_amount+$amt_ttl;
                array_push($rowData,$m,$reports->patient_name,$reports->patient_code,$reports->disease,date('d M Y',strtotime($reports->booking_date)),date('d M Y',strtotime($reports->reminder_date)));
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
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(20);
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


              // added on 31-jan-2018
                //$objPHPExcel->getActiveSheet()->getStyle('B'.$row.':D'.$row.'')->getFont()->setBold( true );
               // $objPHPExcel->getActiveSheet()->setCellValue('D'.$row.'','Total');
                //$objPHPExcel->getActiveSheet()->setCellValue('E'.$row.'',$amt_ttl);
                
                $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(15);
              // added on 31-jan-2018
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
        // Sending headers to force the user to download the file
         header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=pathology_summary_report_".time().".xls"); 
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
        $users_data = $this->session->userdata('auth_users'); 
        $this->load->model('test/test_model','test');
        $this->load->model('general/general_model','general_model');
        $post = $this->input->post(); 
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('search_data',$post); 
        }
        $data['referal_doctor_list'] = $this->test->referal_doctor_list($users_data['parent_id'],'1');
        $data['attended_doctor_list'] = $this->test->attended_doctor_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();

        //$data['dept_list'] = $this->general_model->department_list(5); 
        $data['dept_list'] = $this->general_model->active_department_list(5); 
        $data['employee_user_list'] = $this->general_model->branch_user_list();        
        $data['employee_list'] = $this->test->employee_list();
        $data['profile_list'] = $this->test->profile_list();
        $data['search_data'] = $this->session->userdata('search_data');
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $users_data = $this->session->userdata('auth_users'); 
           $branch_id = $users_data['parent_id'];
           if(isset($search_data['branch_id']) && !empty($search_data['branch_id']))
           {
              $branch_id = $search_data['branch_id'];
           }

if(isset($search_data['branch_id']) && !empty($search_data['branch_id']))
           {
              $branch_id = $search_data['branch_id'];
           }
            if(isset($search_data['referred_by']) && !empty($search_data['referred_by']))
           {
              $referred_by = $search_data['referred_by'];
           }
           else
           {
             $referred_by='';
           }
            if(isset($search_data['referral_hospital']) && !empty($search_data['referral_hospital']))
           {
              $referral_hospital = $search_data['referral_hospital'];
           }
           else
           {
             $referral_hospital='';
           }
            if(isset($search_data['refered_id']) && !empty($search_data['refered_id']))
           {
              $refered_id = $search_data['refered_id'];
           }
           else
           {
            $refered_id='';
           }
           $data['form_data'] = array(
                                   'branch_id'=>$branch_id,
                                   'start_date'=>$search_data['start_date'],
                                   //'referral_doctor'=>$search_data['referral_doctor'],
                                   //'dept_id'=>$search_data['dept_id'],
                                   'patient_code'=>$search_data['patient_code'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'attended_doctor'=>$search_data['attended_doctor'],
                                   'profile_id'=>$search_data['profile_id'],
                                   'sample_collected_by'=>$search_data['sample_collected_by'],
                                   'staff_refrenace_id'=>$search_data['staff_refrenace_id'],
                                   'referred_by'=>$referred_by,
                                  'referral_hospital'=>$referral_hospital,

                                  'refered_id'=>$refered_id,
                                   'department'=>$search_data['department'],
                                   'employee'=>$search_data['employee']
                                 );

           /* $data['form_data'] = array(
                                   'branch_id'=>$branch_id,
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
                                   'staff_refrenace_id'=>$search_data['staff_refrenace_id'],
                                   'department'=>$search_data['department'],
                                   'employee'=>$search_data['employee']
                                 ); */
        }
        else
        {
            $data['form_data'] = array(

                                   'branch_id'=>'',
                                    'start_date'=>'',
                                    "referred_by"=>"",
                                    //'referral_doctor'=>'',
                                    'dept_id'=>'',
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   'patient_code'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'sample_collected_by'=>'',
                                   'staff_refrenace_id'=>'',
                                   'department'=>'',
                                   'employee'=>'',

                                 );
        }  
        $this->load->view('pathology_diseases_report/advance_search',$data);
    }

    public function pdf_path_report()
    {   //unauthorise_permission('147','893');
        $data['print_status']="";
        $data_list = $this->reports->search_report_data();
        
        
        $data['data_list'] = $data_list['path_coll'];
        $data['path_coll_pay_mode'] = $data_list['path_coll_pay_mode'];
        
        $this->load->view('pathology_diseases_report/path_report_html',$data);
        $html = $this->output->get_output();

        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("pathology_summary_report_".time().".pdf");
    }

    public function print_path_report()
    {   unauthorise_permission('147','894');
        $data['print_status']="1";
        $data_list = $this->reports->search_report_data();
        
        
        $data['data_list'] = $data_list['path_coll'];
        $data['path_coll_pay_mode'] = $data_list['path_coll_pay_mode'];
        $this->load->view('pathology_diseases_report/path_report_html',$data); 
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
    public function print_path_expenses_reports()
    {
    unauthorise_permission('43','252');
     $get = $this->input->get();
     $data['expense_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['expense_list'] = $this->reports->get_expenses_details($get);
     } 
     $data['get'] = $get;
     $this->load->view('pathology_diseases_report/list_expenses_reports',$data);  

    }
     public function print_path_collection_reports()
    {
     unauthorise_permission('42','245');
     $get = $this->input->get();
     $users_data = $this->session->userdata('auth_users');
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
     $data['get'] = $get;  
     //echo '<pre>';print_r($data['doctor_collection_list']);die;
     $this->load->view('pathology_diseases_report/list_collection_report_data',$data);  

    }
    public function print_pdf()
    {
          $get = $this->input->get();
          $users_data = $this->session->userdata('auth_users');
          $data['expense_list'] = $this->reports->get_expenses_details($get); 
          $branch_list= $this->session->userdata('sub_branches_data');
          if(!empty($branch_list)){
               $branch_ids = array_column($branch_list, 'id');
          }else{
               $branch_ids='';
          } 
          $data['branch_collection_list'] = [];
          $data['doctor_collection_list'] = $this->reports->doctor_collection_list($get);
          $data['self_collection_list'] = $this->reports->self_collection_list($get);
          if(!empty($branch_ids))
          {
               $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
          }else{
               $data['branch_collection_list'] = $this->reports->branch_collection_list($get,$branch_ids);
          }
        
          $data['get'] = $get;
          


        $html = $this->load->view('pathology_diseases_report/list_collection_report_data',$data,TRUE); 
        //echo $html; exit;
        //$html = $this->output->get_output();die;
        $pdfFilePath ='collection_report.pdf'; 
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
    }

    public function advance_report()
    {
       $data['page_title'] = 'Advance Report';
       $post = $this->input->post();
       $this->load->model('general/general_model','general');
       $data['department_list'] = $this->general->active_department_list(5);
       $data['users_list'] = $this->general->users_list();
       $this->load->model('doctors/doctors_model','doctors');
       $data['attended_doctors_list'] = $this->doctors->attended_doctors_list();
       $data['referral_doctors_list'] = $this->doctors->referral_doctors_list();
       $this->load->model('payment_mode/payment_mode_model','payment_mode');
       $data['payment_mode_list'] = $this->payment_mode->payment_mode_list();
       $this->load->view('pathology_diseases_report/advance_report',$data);
    }

    public function email_report()
    { 
          $users_data = $this->session->userdata('auth_users');
          //print_r(); exit;
          //create pdf
          $this->advance_report_generate(1);
          $email = $post['email'];
          $subject = 'Report '.date('d-m-Y');
          $message = 'Please find the attachment';
          $file_name = str_replace(' ', '-',$users_data['user_name']);
          $attachment = DIR_UPLOAD_PATH.'temp/'.$file_name.'_report.pdf';  
          $this->load->library('general_library');

          $email = get_setting_value('REPORTING_EMAIL_ADDRESS');
          if(!empty($email))
          {
            $email_list = explode(",",$email);
            foreach ($email_list as $email_id) 
            {
              
                $response = $this->general_library->email($email_id,$subject,$message,'',$attachment,'','','','',$booking_data->branch_id);

                if(!empty($attachment) && file_exists($attachment)) 
                {
                  unlink($attachment);
                } 
            }


          }
          
           echo 1;
           return false;
      }

    public function advance_report_generate($seg="")
    { 
       if($seg==1)
       {
          $branch_data = $this->reports->branch_report_data();  
          if(!empty($branch_data))
          {
            $post = json_decode($branch_data[0]['default_search_data'],true);
            $post['start_date'] = date('d-m-Y');
            $post['end_date'] = date('d-m-Y');
          }
          else
          {
            $post = [];
          }
       }
       else
       {
         $post = $this->input->post();    
       }
       //echo "<pre>";print_r($post);die;
       $data['report_wise'] = ""; 
       $data['post'] = $post;  
       if(!empty($post))
       {
          $data['sumrz_dept_list'] = [];
          $data['sumrz_test_list'] =[];
          $data['dtl_dept_list'] =[];
          $data['dtl_user_list'] =[];
          $data['post'] = $post;
          if($post['report_type']==1)
          {  
               $data['sumrz_dept_list'] = $this->reports->summerize_dept_list($post); 
               $data['sumrz_test_list'] = $this->reports->summerize_test_booking_list($post); 
               $data['sumrz_users_list'] = $this->reports->summerize_users_booking_list($post);  
               $data['sumrz_att_doc_list'] = $this->reports->summerize_att_doc_booking_list($post);  
               $data['sumrz_ref_doc_list'] = $this->reports->summerize_ref_doc_booking_list($post); 
               //$data['sumrz_patient_list'] = $this->reports->summerize_patient_booking_list($post); 
               $data['sumrz_pay_mode_list'] = $this->reports->summerize_pay_mode_booking_list($post);
          }
          else if($post['report_type']==2)
          { 
             if($post['collection_group']=='1')
             {
               $data['report_wise'] = "Department wise";
               $data['dtl_dept_list'] = $this->reports->details_dept_list($post);
             }
             else if($post['collection_group']=='2')
             {
               $data['report_wise'] = "Test wise";
               $data['dtl_test_list'] = $this->reports->details_test_list($post);
               $data['total_entities'] = $this->reports->details_test_total_entities($post);
             }
             else if($post['collection_group']=='3')
             {
               $data['report_wise'] = "Users wise";
               $data['dtl_user_list'] = $this->reports->details_users_list($post);
             }
             else if($post['collection_group']=='4')
             {
               $data['report_wise'] = "Attended doctor wise";
               $data['dtl_att_doc_list'] = $this->reports->details_att_doc_list($post);
             }
             else if($post['collection_group']=='5')
             {
               $data['report_wise'] = "Referral doctor wise";
               $data['dtl_ref_doc_list'] = $this->reports->details_ref_doc_list($post);
             }
             else if($post['collection_group']=='7')
             {
               $data['report_wise'] = "Payment mode wise";
               $data['dtl_pay_mode_list'] = $this->reports->details_pay_mode_list($post);
             }
          }
          else if($post['report_type']==3)
          {  
           $data['date_wise_list'] = $this->reports->date_wise_list($post);
          }
          if(!empty($seg))
          {
            $test_report_data = $this->load->view('pathology_diseases_report/advance_report_data',$data,true);
            $users_data = $this->session->userdata('auth_users');
            $this->load->library('m_pdf'); 
            $file_name = str_replace(' ', '-',$users_data['user_name']);
            $pdfFilePath = $file_name.'_report.pdf'; 
            $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
            $this->m_pdf->pdf->WriteHTML($test_report_data,2);
            $this->m_pdf->pdf->Output($pdfFilePath, "F");
          }
          else
          { //echo "<pre>"; print_r($data); exit;
            $this->load->view('pathology_diseases_report/advance_report_data',$data);
          }
          
       } 
    }


 
    
     
}
?>