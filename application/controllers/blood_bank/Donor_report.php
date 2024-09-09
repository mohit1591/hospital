<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/report/Donor_report_model','donor_report');
        $this->load->library('form_validation');
    }
    

    public function index()
    {  
        unauthorise_permission('268','1560');
        $this->session->unset_userdata('donor_report_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('donor_report_search_data');
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
        $data['form_data'] = array('from_date'=>$start_date, 'end_date'=>$end_date,'branch_id'=>'');
        $data['page_title'] = 'Donor Report list';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        $this->load->view('blood_bank/donor_report/list',$data);
    }
    
    public function ajax_list()
    {  
        unauthorise_permission('260','1560');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->donor_report->get_datatables();
        //echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $grand_total_amount =0;
        $grand_total_discount=0;
        //$grand_net_amount=0;
        $grand_paid_amount=0;
        $grand_balance_amount=0;
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
               
               $alert_days = get_setting_value('BLOODBANK_COMPONENT_EXPIRY_DAY');
            $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
            $expire_alert_days = date('Y-m-d',$expire_timestamp);
            $current_date = date('Y-m-d');
            $expire_date = date('d-m-Y H:i:s',strtotime($reports->expiry_date));
            if($reports->expiry_date!='0000-00-00' && $reports->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
            {
              if($current_date>=$expire_alert_days)
              {
                $expire_date = $expire_date;  
              }
              else
              {
                $expire_date = $expire_date; 
              }
              
            }
            else
            {
              $expire_date = ''; 
            }
            
            $status_flag='';  
            if($reports->flag==2)
            {
               $status_flag='Issued'; 
               
            }
            else if($reports->flag==1)
            {
                //echo $qty;
               
                $check_beg_qcy= check_beg_qc($reports->donor_id);
                //print_r($check_beg_qcy);  die;
                if(strtotime(date('d-m-Y H:i:s',strtotime($reports->expiry_date)))<strtotime(date('d-m-Y H:i:s')) && $check_beg_qcy[0]->blood_condition!=2)
                {
                     $status_flag="Expired";
                }
               
                else if(count($check_beg_qcy)>0)
                {
                   
                   if($check_beg_qcy[0]->blood_condition==2)
                   {
                        $status_flag="Discard"; 
                   }
                   else
                   {
                       $status_flag="Tested"; 
                   }
                }
                else
                {
                    $status_flag="Untested";
                }
               
                
            }
                            
               ////////// Check list end ///////////// 
            $no++;
            $row = array(); 
           
            $row[] = $reports->donor_name;  
              
            $row[] = $reports->donor_code;
            $row[] = $reports->mobile_no;
            //$row[] = date('d-m-Y',strtotime($reports->registration_date));
            $row[] = $reports->blood_group;
            $row[] = $reports->component_name;
            $row[] = $reports->qty;
            $row[] = $expire_date;
            $row[] = $status_flag;
            
            
           
           
               $print_url = "'".base_url('blood_bank/recipient/print_issue_recipt/'.$reports->id)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             
            $btn_edit = '';//' <a class="btn-custom" href="'.base_url("sales_medicine/edit/".$reports->id).'" title="Edit Booking"><i class="fa fa-pencil"></i> Edit</a>';
                    

              
           // $row[] = $btn_print; 
            $row[] = $btn_edit;         
            $data[] = $row;

            
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->donor_report->count_all(),
                        "recordsFiltered" => $this->donor_report->count_filtered(),
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
                                   'referral_doctor'=>'',
                                   'sale_no'=>'',
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>$post['end_date'],
                                   'branch_id'=>$post['branch_id']
                                   
                                 );
         $this->session->set_userdata('donor_report_search_data',$search_data);
       }
    }

    

    public function reset_date_search()
    {
       $this->session->unset_userdata('donor_report_search_data');
    }

    public function donor_report_excel()
    {
         
        unauthorise_permission('268','1568');
              // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $grand_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
          $objWorksheet = $objPHPExcel->getActiveSheet();
         // print_r($objWorksheet);die;

          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
          $fields = array('Donor Name','Donor Code' ,'Mobile No.','Blood Group','Component','Qty','Expiry Date','Status');
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
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
               
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
			
          $list =  $this->donor_report->search_report_data();
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
                    
                        $alert_days = get_setting_value('BLOODBANK_COMPONENT_EXPIRY_DAY');
                        $expire_timestamp = strtotime($reports->expiry_date)-(86400*$alert_days);
                        $expire_alert_days = date('Y-m-d',$expire_timestamp);
                        $current_date = date('Y-m-d');
                        $expire_date = date('d-m-Y H:i:s',strtotime($reports->expiry_date));
                        if($reports->expiry_date!='0000-00-00' && $reports->expiry_date!='00-00-0000' && $expire_date!='01-01-1970' && $expire_date!='00-00-0000' && $expire_date!='0000-00-00')
                        {
                          if($current_date>=$expire_alert_days)
                          {
                            $expire_date = $expire_date;  
                          }
                          else
                          {
                            $expire_date = $expire_date; 
                          }
                          
                        }
                        else
                        {
                          $expire_date = ''; 
                        }
                        
                        $status_flag='';  
                        if($reports->flag==2)
                        {
                           $status_flag='Issued'; 
                           
                        }
                        else if($reports->flag==1)
                        {
                            //echo $qty;
                           
                            $check_beg_qcy= check_beg_qc($reports->donor_id);
                            //print_r($check_beg_qcy);  die;
                            if(strtotime(date('d-m-Y H:i:s',strtotime($reports->expiry_date)))<strtotime(date('d-m-Y H:i:s')) && $check_beg_qcy[0]->blood_condition!=2)
                            {
                                 $status_flag="Expired";
                            }
                           
                            else if(count($check_beg_qcy)>0)
                            {
                               
                               if($check_beg_qcy[0]->blood_condition==2)
                               {
                                    $status_flag="Discard"; 
                               }
                               else
                               {
                                   $status_flag="Tested"; 
                               }
                            }
                            else
                            {
                                $status_flag="Untested";
                            }
                           
                            
                        }
            
                
                  array_push($rowData,$reports->donor_code,$reports->donor_code,$reports->mobile_no,$reports->blood_group,$reports->component_name,$reports->qty,$expire_date,$status_flag);
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
                // added on 31-jan-2018
                //$objPHPExcel->getActiveSheet()->getStyle('D'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // added on 31-jan-2018
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=donor_report_report_".time().".xls");  
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
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['attended_doctor_list'] = $this->opd->attended_doctor_list();
        $data['dept_list'] = $this->general_model->department_list(); 
        $data['employee_list'] = $this->general_model->branch_user_list();

        $data['profile_list'] = $this->opd->profile_list();
        $data['search_data'] = $this->session->userdata('donor_report_search_data');
        
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                                   'from_date'=>$search_data['from_date'],
                                   'patient_name'=>$search_data['patient_name'],
                                   'mobile_no'=>$search_data['mobile_no'],
                                   'end_date'=>$search_data['end_date'],
                                   'refered_id'=>$search_data['refered_id'],
                                    'referred_by'=>$search_data['referred_by'],
                                     'referral_hospital'=>$search_data['referral_hospital'],
                                   'branch_id'=>$search_data['branch_id'],
                                   'employee'=>$search_data['employee'],
                                   'component_id'=>$search_data['component_id'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'from_date'=>'',
                                   "referred_by"=>"",
                                    "refered_id"=>"",
                                    "referral_hospital"=>"",
                                   'patient_name'=>'',
                                   'mobile_no'=>'',
                                   'end_date'=>'',
                                   'attended_doctor'=>'',
                                   'profile_id'=>'',
                                   'branch_id'=>'',
                                   'employee'=>'',
                                   'component_id'=>'',
                                 );
        }  
          if(isset($post) && !empty($post))
          {
          $marge_post = array_merge($data['form_data'],$post);
          $this->session->set_userdata('donor_report_search_data', $marge_post);
          }
      
        $this->load->view('blood_bank/donor_report/advance_search',$data);
    }


    public function donor_pdf_report()
    {   
         unauthorise_permission('268','1569');
        $data['print_status']="";
        
        $data['data_list'] = $this->donor_report->search_report_data();
        $this->load->view('blood_bank/donor_report/donor_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("donor_report_".time().".pdf");
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
    
    public function donor_print_report()
    {   
        unauthorise_permission('268','1569');
        $data['print_status']="1";
        $data['data_list'] = $this->donor_report->search_report_data();
        $this->load->view('blood_bank/donor_report/donor_report_html',$data); 
    }

    
     
}
?>