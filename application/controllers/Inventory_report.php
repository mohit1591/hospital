<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('inventory_report/inventory_report_model', 'inventory_report');
        $this->load->library('form_validation');
    }

    public function index()
    {
    }

    public function inventory_purchase()
    {
        unauthorise_permission('173', '1006');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Purchase Reports';
        $this->load->view('inventory_report/inventory_purchase_report', $data);
    }

    public function inventory_purchase_return()
    {
        unauthorise_permission('173', '1007');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Purchase Return Reports';
        $this->load->view('inventory_report/inventory_purchase_return_report', $data);
    }
    public function inventory_allotment()
    {
        unauthorise_permission('173', '1008');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Allotment Reports';
        $this->load->view('inventory_report/inventory_allotment_report', $data);
    }

    public function inventory_allotment_return()
    {
        unauthorise_permission('173', '1009');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Allotment Return Reports';
        $this->load->view('inventory_report/inventory_allotment_return', $data);
    }
    public function garbage()
    {
        unauthorise_permission('173', '1010');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Garbage Reports';
        $this->load->view('inventory_report/garbage_report', $data);
    }
    public function stock_item()
    {
        unauthorise_permission('173', '1011');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Stock Reports';
        $this->load->view('inventory_report/inventory_stock_report', $data);
    }



    public function print_inventory_reports()
    {

        $get = $this->input->get();
        $users_data = $this->session->userdata('auth_users');
        $data['print_status'] = "";
        //$branch_list= $this->session->userdata('sub_branches_data');
        //$parent_id= $users_data['parent_id'];
        //$branch_ids = array_column($branch_list, 'id'); 

        $data['branch_inventory_list'] = [];
        $data['branch_inventory_list'] = $this->inventory_report->branch_inventory_list($get);

        $data['get'] = $get;
        if ($get['section_type'] == 3 || $get['section_type'] == 4) {
            $this->load->view('inventory_report/list_allotment_inventory_report_html', $data);
        }

        if ($get['section_type'] == 1 || $get['section_type'] == 2) {

            $this->load->view('inventory_report/list_purchase_inventory_report_html', $data);
        }
        if ($get['section_type'] == 5) {

            $this->load->view('inventory_report/list_garbage_report_html', $data);
        }
        if ($get['section_type'] == 6) {

            $this->load->view('inventory_report/list_stock_report_html', $data);
        }
    }
    public function inventory_purchase_report_pdf()
    {
        $get = $this->input->get();
        $data['print_status'] = "";
        $data['branch_inventory_list'] = $this->inventory_report->branch_inventory_list($get);
        $data['get'] = $get;

        if ($get['section_type'] == 3 || $get['section_type'] == 4) {
            $this->load->view('inventory_report/list_allotment_inventory_report_html', $data);
        }

        if ($get['section_type'] == 1 || $get['section_type'] == 2) {

            $this->load->view('inventory_report/list_purchase_inventory_report_html', $data);
        }
        if ($get['section_type'] == 5) {
            $this->load->view('inventory_report/list_garbage_report_html', $data);
        }
        if ($get['section_type'] == 6) {
            $this->load->view('inventory_report/list_stock_report_html', $data);
        }

        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("inventory_report_data_report_" . time() . ".pdf");
    }

    public function inventory_purchase_report_excel()
    {
        $get = $this->input->get();
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $purchase_date = '';
        $purchase_code = '';
        $allotment_date = '';
        $allotment_code = '';

        // Field names in the first row
        if ($get['section_type'] == 1 || $get['section_type'] == 2) {
            if ($get['section_type'] == 1) {
                $purchase_date = 'Purchase Date';
            }
            if ($get['section_type'] == 2) {
                $purchase_date = 'Return Date';
            }
            if ($get['section_type'] == 1) {
                $purchase_code = 'Purchase Code';
            }
            if ($get['section_type'] == 2) {
                $purchase_code = 'Return Code';
            }



            $fields = array($purchase_code, $purchase_date, 'Vendor Name', 'Net Amount', 'Paid Amount', 'Balance');
        }
        if ($get['section_type'] == 3 || $get['section_type'] == 4) {
            if ($get['section_type'] == 3) {
                $allotment_date = 'Issue Date';
            }
            if ($get['section_type'] == 4) {
                $allotment_date = 'Return Date';
            }
            if ($get['section_type'] == 3) {
                $allotment_code = 'Issue Code';
            }
            if ($get['section_type'] == 4) {
                $allotment_code = 'Return Code';
            }
            $fields = array($allotment_code, 'Patient Name/Employee Name/Doctor Name', 'Total Amount', $allotment_date);
        }

        if ($get['section_type'] == 5) {
            $fields = array('Garbage Code', 'Remarks', 'Total Amount', 'Garbage Date');
        }
        if ($get['section_type'] == 6) {
            $fields = array('Sr.No.', 'Category', 'Item Name', 'MRP', 'Price', 'Stock', 'Issue', 'Purchase');
        }
        $col = 0;
        foreach ($fields as $field) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            $col++;
        }
        $users_data = $this->session->userdata('auth_users');

        $branch_list = $this->session->userdata('sub_branches_data');
        $parent_id = $users_data['parent_id'];
        $branch_ids = array_column($branch_list, 'id');
        // print_r($branch_ids );die;
        $data['branch_gst_list'] = [];
        $list = $this->inventory_report->branch_inventory_list($get);
        $rowData = array();
        $data = array();
        if (!empty($list)) {

            $i = 1;
            $purchase_no = '';
            $issue_no = '';
            foreach ($list as $reports) {

                if ($get['section_type'] == 1 || $get['section_type'] == 2) {

                    if ($get['section_type'] == 1) {
                        $purchase_no = $reports->purchase_no;
                    }
                    if ($get['section_type'] == 2) {
                        $purchase_no = $reports->return_no;
                    }

                    array_push($rowData, $purchase_no, date('d-m-Y', strtotime($reports->purchase_date)), $reports->name, $reports->net_amount, $reports->paid_amount, $reports->balance);
                }

                if ($get['section_type'] == 3 || $get['section_type'] == 4) {
                    if ($get['section_type'] == 3) {
                        $issue_no = $reports->issue_no;
                    }
                    if ($get['section_type'] == 4) {
                        $issue_no = $reports->return_no;
                    }
                    array_push($rowData, $issue_no, $reports->member_name, $reports->total_amount, date('d-m-Y', strtotime($reports->issue_date)));
                }
                if ($get['section_type'] == 5) {


                    array_push($rowData, $reports->garbage_no, $reports->remarks, $reports->total_amount, date('d-m-Y', strtotime($reports->garbage_date)));
                }
                if ($get['section_type'] == 6) {

                    $qty_data = get_item_quantity($reports->id, $reports->category_id);
                    $stock = $qty_data['total_qty'];
                    $get1 = $this->db->select('SUM(CASE WHEN path_stock_item.type = 1 THEN  path_stock_item.debit ELSE 0 END) AS purchase_qty,
                    SUM(CASE WHEN path_stock_item.type = 6 THEN  path_stock_item.credit ELSE 0 END) AS issue_qty')
                    ->from('path_stock_item')
                    ->where('path_stock_item.item_id',$reports->item_id)
                    ->group_by('item_id')
                    ->limit(1)
                    ->get();
                    $issueQty = 0;
                    $purchaseQty = 0;
                    if($get1->num_rows()){
                        $row = $get1->row();
                        $issueQty = $row->issue_qty;
                        $purchaseQty = $row->purchase_qty;
                    }
                    
                    array_push(
                        $rowData,
                        $i,
                        $reports->category,
                        $reports->item,
                        $reports->mrp,
                        $reports->price,
                        $stock,
                        $issueQty,
                        $purchaseQty
                    );
                }

                $count = count($rowData);
                for ($j = 0; $j < $count; $j++) {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;
            }
        }
        // Fetching the table data
        $row = 2;
        if (!empty($data)) {
            foreach ($data as $reports_data) {
                $col = 0;
                
                foreach ($fields as $field) {
                    // echo $reports_data[$field]; die;
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        }

        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=inventory_report_" . time() . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        if (!empty($data)) {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }

    public function inventory_allotment_advance_report()
    {
        unauthorise_permission('173', '1008');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Allotment Advance Reports';
        $this->load->view('inventory_report/inventory_allotment_advance_report', $data);
    }


    public function print_inventory_advance_reports()
    {

        $get = $this->input->get();
        $users_data = $this->session->userdata('auth_users');
        $data['print_status'] = "";

        $data['branch_inventory_list'] = [];
        $data['branch_inventory_list'] = $this->inventory_report->print_inventory_advance_reports($get);

        $data['get'] = $get;
        if ($get['section_type'] == 3 || $get['section_type'] == 4) {
            $this->load->view('inventory_report/list_allotment_advance_report_html', $data);
        }
    }

    public function inventory_dialysis_report()
    {
        //unauthorise_permission('173','1008');
        //$data['medicine_gst_list'] = $this->inventory_report->gst_list();
        $data['page_title'] = 'Inventory Dialysis Reports';
        $this->load->view('inventory_report/inventory_dialysis_report', $data);
    }

    public function print_inventory_dialysis_reports()
    {

        $get = $this->input->get();
        $users_data = $this->session->userdata('auth_users');
        $data['print_status'] = "";

        $data['branch_inventory_list'] = [];
        $data['branch_inventory_list'] = $this->inventory_report->print_inventory_dialysis_reports($get);

        $data['get'] = $get;

        $this->load->view('inventory_report/list_allotment_dialysis_report_html', $data);
    }
}
