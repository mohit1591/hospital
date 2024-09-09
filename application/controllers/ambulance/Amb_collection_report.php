<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amb_collection_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ambulance/collection_reports_model','collection_report');
    }


   public function reports(){
          unauthorise_permission('349','2086');
          $data['page_title'] = 'Ambulance Collection Report';
          $this->load->model('ambulance/booking_model','booking');
          $this->load->model('general/general_model');
          $data['referal_doctor_list'] = $this->booking->referal_doctor_list();
          $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
          $data['referal_hospital_list'] = $this->booking->referal_hospital_list();
          $data['vehicle_list'] = $this->booking->vehicle_list();
          $data['driver_list'] = $this->booking->driver_list();
           $data['employee_list'] = $this->booking->employee_list();
          $data['particulars_list'] = $this->general_model->amb_particulars_list();
          $data['vendor_list'] = $this->general_model->amb_vendor_list();
          $data['location_list'] = $this->general_model->location_list();
          $data['payment_mode'] = $this->general_model->payment_mode();
           $data['user_list'] = $this->general_model->branch_user_list();
          $this->load->view('ambulance/collection_report/collection_report',$data);
    }

    public function get_document_data($get="")
    {

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $parent_id= $users_data['parent_id'];
      $data['collection_list'] = $this->collection_report->ambulance_collection_report($get);
      $data['get'] = $get;
      $this->load->view('ambulance/collection_report/collection_report_data',$data); 
    }  
     public function get_collection_data($get="")
         {
              unauthorise_permission('349','2086');
              $get = $this->input->get();
            //  print_r($get);die;
              $users_data = $this->session->userdata('auth_users');
            
               $data['self_ambulance_collection_list'] = $this->collection_report->ambulance_collection_report($get);
              // print_r( $data['self_ambulance_collection_list']);die;
              $data['get'] = $get;
              $data['collection_tab_setting'] = $this->collection_report->get_collection_tab_setting();
              
             $all_expense_list = $this->collection_report->get_expenses_details($get);
            $all_expense_lists = $all_expense_list['expense_list']; 
              
            $price = array(); 
            foreach ($all_expense_lists as $key => $row)  
            { 
                $price[$key] = $row->expenses_date; 
            } 
            array_multisort($price, SORT_DESC, $all_expense_lists); 
            // echo "<pre>"; print_r($all_expense_lists);die();
            $data['all_expense_list']['expense_list'] = $all_expense_lists;
              if(!empty($get['send']) && $get['send']=='preview')
              {
                $this->load->view('ambulance/collection_report/list_collection_report_preview',$data);
              }
              else
              {
                $from_date = $get['start_date'];
                $to_date = $get['end_date'];
                  
                $middle_replace_part=$this->load->view('ambulance/collection_report/list_collection_report_data',$data,true);
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
                $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
            }
    
    }   
}
?>