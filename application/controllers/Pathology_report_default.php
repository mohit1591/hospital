<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_report_default extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pathology_reports/reports_model','reports');
        $this->load->library('form_validation');
    }

  	public function default_search_data()
    {
       $post = $this->input->post();
       if(!empty($post))
       {
          $this->load->model('general/general_model'); 
         	$default_id =  $this->general_model->save_pathology_default();
         	echo 1;
       }
    }

    public function email_report()
    { 
          $users_data = $this->session->userdata('auth_users');
          //print_r(); exit;
          //create pdf
          $this->generate_pdf($users_data['parent_id']);
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

      public function generate_pdf()
      {
        $users_data = $this->session->userdata('auth_users');
        $this->load->library('m_pdf');
        $test_report_data ='';

         

        $file_name = str_replace(' ', '-',$users_data['user_name']);
        $pdfFilePath = $file_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        $this->m_pdf->pdf->WriteHTML($test_report_data,2);
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
    }


}
?>    