<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccine_billing_report extends CI_Controller {

		public function __construct()
		{
				parent::__construct();
				auth_users();
				$this->load->model('vaccine_billing_report/vaccine_billing_model','vaccine_bill');
				$this->load->library('form_validation');
		}
		 public function vaccine_bill_report(){
		 	
			 //unauthorise_permission('42','245');
			 //$data['employee_list'] = $this->reports->employee_list();
             $get= $this->input->get();
			 $data['page_title'] = 'Vaccine Billing Reports';
			 $data['from_c_date'] = date('d-m-Y');
			 $data['to_c_date'] =   date('d-m-Y'); 
			 $this->load->view('vaccine_billing_report/vaccine_billing_report',$data);
		}
		
		public function print_vaccine_billing_reports()
		{ 
			$get = $this->input->get();
			$users_data = $this->session->userdata('auth_users');
		     $data['bill_list'] = $this->vaccine_bill->vaccine_bill_list($get);	
		    $data['get'] = $get;
			$this->load->view('vaccine_billing_report/list_collection_report_data',$data);  
		}
		public function report_excel()
		{
				$get = $this->input->get();
				$list =  $this->vaccine_bill->vaccine_bill_list($get);
				// Starting the PHPExcel library
				$this->load->library('excel');
				$this->excel->IO_factory();
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
				$objPHPExcel->setActiveSheetIndex(0);
				// Field names in the first row
				$fields = array('Sr.No.','Vaccine Name','Patient Name','Date','Net Amount','Paid Amount','Balance');
				$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$col = 0;
				$row_heading=1;
				foreach ($fields as $field)
				{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
						$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
						
						$objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						$objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
						$col++;
						$row_heading++;
				}
					
				//$this->reports->search_report_data($get);
				$rowData = array();
				$data= array();
				if(!empty($list))
				{
						
						$i=0;
						$k=1;
						$totalnet='';
						$totalpaid='';
						$totalbalance='';
						$objPHPExcel->getActiveSheet()->getRowDimension($k)->setRowHeight(18);
						foreach($list as $vaccine)
						{
							$objPHPExcel->getActiveSheet()->getRowDimension($k+1)->setRowHeight(18);
								array_push($rowData,$k,$vaccine['vaccination_name'],$vaccine['patient_name'],date('d-m-Y',strtotime($vaccine['sale_date'])),$vaccine['net_amount'],$vaccine['paid_amount'],$vaccine['balance']);
								$count = count($rowData);
								for($j=0;$j<$count;$j++)
								{
										$data[$i][$fields[$j]] = $rowData[$j];
								}
								unset($rowData);
								$rowData = array();
								$i++; 
								$k++; 
							$totalnet +=$vaccine['net_amount'];
       						$totalpaid +=$vaccine['paid_amount'];
       						$totalbalance +=$vaccine['balance'];
						}
						$objPHPExcel->getActiveSheet()->getRowDimension($k+1)->setRowHeight(18);
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
						$objWorksheet->setCellValueByColumnAndRow(3,$row+1,'Total');
                          $objWorksheet->setCellValueByColumnAndRow(4,$row+1,$totalnet);
                          $objWorksheet->setCellValueByColumnAndRow(5,$row+1,$totalpaid);
                          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,$totalbalance);
                         
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

}
?>