<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('material_report/material_report_model','ipd_discharge_summary');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('124','749');
        $data['page_title'] = 'Material Report'; 
        $data['form_data'] = array('treatment_name'=>'','start_date'=>date('d-m-Y'), 'end_date'=>date('d-m-Y')); 
        
        $this->load->view('material_report/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('124','749');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->ipd_discharge_summary->get_datatables();

          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_discharge_summary) {
        //echo "<pre>"; print_r($ipd_discharge_summary);die;
            $no++;
            $row = array();
            $check_script = "";
            if($i==$total_num)
            {
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
            $checkboxs = "";
            if($users_data['parent_id']==$ipd_discharge_summary->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_discharge_summary->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $i;
            $row[] = $ipd_discharge_summary->booking_code;
            $row[] = $ipd_discharge_summary->patient_name;
            
            $row[] = $ipd_discharge_summary->treatment_name;
            $row[] = $ipd_discharge_summary->teeth_name;
            $row[] = $ipd_discharge_summary->tooth_number;
            $row[] = $ipd_discharge_summary->treatment_type_id;
            $row[] = $ipd_discharge_summary->treatment_remarks;
            
            $row[] = date('d-m-Y',strtotime($ipd_discharge_summary->booking_date));
          
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_discharge_summary->count_all(),
                        "recordsFiltered" => $this->ipd_discharge_summary->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function delete($id="")
    {
      
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_discharge_summary->delete($id);
           $response = "Patient death details deleted successfully.";
           echo $response;
       }
    }
    

    public function advance_search()
    {

          $data['page_title'] = "Advance Search";
          $post = $this->input->post();

          $data['form_data'] = array(
                                       "start_date"=>"",
                                      "end_date"=>"",
                                      "treatment_name"=>"",
                                      
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('material_summary_search', $marge_post);
          }
          $material_summary_search = $this->session->userdata('material_summary_search');
          if(isset($material_summary_search) && !empty($material_summary_search))
          {
              $data['form_data'] = $material_summary_search;
          }
          //$this->load->view('material_report/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('material_summary_search');
    }


    public function material_excel()
    {
        
    $this->load->library('excel');
    $this->excel->IO_factory();
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
    $objPHPExcel->setActiveSheetIndex(0);
     
        $fields = array('S.No.','OPD No.','Patient Name','Treatment Name','Teeth Type','Tooth No.','Material','Remarks','Date');
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        $list = $this->ipd_discharge_summary->search_report_data();
    
    $rowData = array();
    $data= array();
    if(!empty($list))
    {

      $i=0;
      $k=1;
      foreach($list as $reports)
      {
          
         
                array_push($rowData,$k,$reports->booking_code,$reports->patient_name,$reports->treatment_name,$reports->teeth_name,$reports->tooth_number,$reports->treatment_type_id,$reports->treatment_remarks,date('d-m-Y',strtotime($reports->booking_date)));
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
               foreach($data as $boking_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

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
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=material_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }


    public function pdf_material()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->ipd_discharge_summary->search_report_data();
        $this->load->view('material_report/material_html',$data);
        $html = $this->output->get_output();
        //print_r($html); exit;
        $this->load->library('pdf');
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ipd_death_list_".time().".pdf");
    }

    public function print_material()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->ipd_discharge_summary->search_report_data();
      $this->load->view('material_report/material_html',$data); 
    }


    

}
?>