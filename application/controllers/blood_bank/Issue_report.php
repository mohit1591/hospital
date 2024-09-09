<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Issue_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/report/Issue_report_model','issue_report');
        $this->load->library('form_validation');
    }
    

    public function index()
    {  
        unauthorise_permission('268','1560');
        $this->session->unset_userdata('issue_report_search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('bloodbank_collection_search_data');
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
        $data['page_title'] = 'Issue Report list';
        $this->load->model('general/general_model','general_model');
        $data['employee_list'] = $this->general_model->branch_user_list();
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        $this->load->view('blood_bank/issue_report/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission('260','1560');
        $users_data = $this->session->userdata('auth_users'); 
        $list = $this->issue_report->get_datatables();
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
        foreach($list as $reports) 
        {
          //print_r($reports);
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
                            
             
            $no++;
            $row = array(); 
            $row[] = $reports->patient_code;  
            $row[] = date('d-m-Y',strtotime($reports->requirement_date));  
            $row[] = $reports->patient_name;
            $row[] = $reports->donor_name;
            $row[] = $reports->donor_code;
            $row[] = $reports->blood_group;
            $row[] = $reports->component;
            $row[] = $reports->unit_qty;
            
            $print_url = "'".base_url('blood_bank/recipient/print_issue_recipt/'.$reports->id)."'";
               $btn_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>';
             $btn_edit = '';
            $row[] = $btn_print; 
            $row[] = $btn_edit;         
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->issue_report->count_all(),
                        "recordsFiltered" => $this->issue_report->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function reset_date_search()
    {
       $this->session->unset_userdata('issue_report_search_data');
    }

    public function issue_report_excel()
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
          $fields = array('Issue Code','Requirement Date' ,'Patient Name','Donor Name','Donor Code','Blood Group','Component','Component Unit');
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
			$this->load->model('general/general_model');
          $list =  $this->issue_report->search_report_data();
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
               		if(!empty($reports->requirement_date))
    				{
    					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
    				} 
    				else
    				{
    					$requirement_date = date('d-m-Y',strtotime($reports->requirement_date));
    				}

                 array_push($rowData,$reports->patient_code,$requirement_date,$reports->patient_name,$reports->donor_name,$reports->donor_code,$reports->blood_group,$reports->component,$reports->unit_qty);
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
                
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=issue_report_".time().".xls");  
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
        $post = $this->input->post();
        $data['search_data'] = $this->session->userdata('issue_report_search_data');
        
        $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['component_list']=$this->blood_general_model->get_component_list();
        
        if(isset($data['search_data']) && !empty($data['search_data']))
        {
           $search_data = $data['search_data'];
           $data['form_data'] = array(
                                   'from_date'=>$search_data['from_date'],
                                   'end_date'=>$search_data['end_date'],
                                   'component_id'=>$search_data['component_id'],
                                 );
        }
        else
        {
            $data['form_data'] = array(
                                   'from_date'=>'',
                                   'end_date'=>'',
                                   'component_id'=>'',
                                 );
        }  
          if(isset($post) && !empty($post))
          {
          $marge_post = array_merge($data['form_data'],$post);
          $this->session->set_userdata('issue_report_search_data', $marge_post);
          }
      
      
     
        //$this->load->view('blood_bank/issue_report/advance_search',$data);
    }


    public function issue_pdf_report()
    {   
         unauthorise_permission('268','1569');
        $data['print_status']="";
        $data['data_list'] = $this->issue_report->search_report_data();
        $this->load->view('blood_bank/issue_report/issue_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("issue_report_".time().".pdf");
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
    

    public function issue_print_report()
    {   
        unauthorise_permission('268','1569');
        $data['print_status']="1";
        
        $data['data_list'] = $this->issue_report->search_report_data();
        $this->load->view('blood_bank/issue_report/issue_report_html',$data); 
    }

    
     
}
?>