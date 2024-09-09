<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banking_report extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    auth_users();
    $this->load->model('canteen/banking_report/banking_report_model','banking_report');
    $this->load->library('form_validation');
  }

  public function index()
  {
    
  }

   public function banking(){
       //$data['medicine_gst_list'] = $this->medicine_gst->gst_list();
       $data['page_title'] = 'Banking Reports';
       $this->load->view('canteen/banking_report/bank_report_add_report',$data);
    }

    public function print_banking_report_reports()
    { 

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];

      if($get['branch_id']=='all'){
        $data['self_branch_bank_list'] = $this->banking_report->self_branch_bank_list($get);
        $branch_ids = array_column($branch_list, 'id'); 
        $data['banking_according_total'][$users_data['parent_id']] = $this->banking_report->banking_according_total($get,$users_data['parent_id']);
      }else{
        $branch_ids=$get['branch_id'];
      }
      
      $data['branch_bank_collection_list']='';
      //
      
     // print_r($data['self_branch_bank_list']);die;
      if(!empty($branch_ids))
      {
        $data['branch_bank_collection_list'] = $this->banking_report->banking_report_list_all_branch($get,$branch_ids);

         if($get['branch_id']=='all'){
            $branch_id = implode(',', $branch_ids); 
             $new_branch_ids=explode(',', $branch_id);

                if(isset( $new_branch_ids)){
            foreach($new_branch_ids as $ids){
              $data['banking_according_total'][$ids] = $this->banking_report->banking_according_total($get,$ids);
            }
          }
            }else{
            $new_branch_ids=$get['branch_id'];
            $data['banking_according_total'][ $new_branch_ids] = $this->banking_report->banking_according_total($get, $new_branch_ids);
            }


          
           //print '<pre>'; print_r($data['banking_according_total']);die;
        
      }
      //print '<pre>';print_r($data['branch_bank_collection_list']);die;
      $data['get'] = $get;
      $data['parent_id']=$users_data['parent_id'];
      $this->load->view('canteen/banking_report/banking_report_data',$data);  
  
}

public function banking_report_pdf()
    {    
      $data['print_status']="";
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
    
      if($get['branch_id']=='all'){
        $data['self_branch_bank_list'] = $this->banking_report->self_branch_bank_list($get);
        $branch_ids = array_column($branch_list, 'id'); 
        $data['banking_according_total'][$users_data['parent_id']] = $this->banking_report->banking_according_total($get,$users_data['parent_id']);
      }else{
        $branch_ids=$get['branch_id'];
      }
      
      $data['branch_bank_collection_list']='';
      //
      
     // print_r($data['self_branch_bank_list']);die;
      if(!empty($branch_ids))
      {
        $data['branch_bank_collection_list'] = $this->banking_report->banking_report_list_all_branch($get,$branch_ids);

         if($get['branch_id']=='all'){
            $branch_id = implode(',', $branch_ids); 
             $new_branch_ids=explode(',', $branch_id);

                if(isset( $new_branch_ids)){
            foreach($new_branch_ids as $ids){
              $data['banking_according_total'][$ids] = $this->banking_report->banking_according_total($get,$ids);
            }
          }
            }else{
            $new_branch_ids=$get['branch_id'];
            $data['banking_according_total'][ $new_branch_ids] = $this->banking_report->banking_according_total($get, $new_branch_ids);
            }


          
           //print '<pre>'; print_r($data['banking_according_total']);die;
        
      }
     // print '<pre>';print_r($data);die;
      //print '<pre>';print_r($data['branch_bank_collection_list']);die;
        $data['get'] = $get;
       $data['parent_id']=$users_data['parent_id'];
       //$this->load->view('canteen/banking_report/banking_report_data',$data);
      $html = $this->load->view('canteen/banking_report/pdf_banking_report_html',$data,TRUE); 
        //$html = $this->output->get_output();
         //print_r($html); exit; 
        // Load library
        $this->load->library('m_pdf');
        //echo $html; exit;
        // Convert to PDF
        $pdfFilePath ='banking_collection_report.pdf'; 
        $this->m_pdf->pdf->WriteHTML($html);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "D");
       // $this->pdf->render();
        //$this->pdf->stream("banking_collection_report_".time().".pdf");
    }

  public function print_banking_report()
    {    
        $data['print_status']="";
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
    
      if($get['branch_id']=='all'){
        $data['self_branch_bank_list'] = $this->banking_report->self_branch_bank_list($get);
        $branch_ids = array_column($branch_list, 'id'); 
        $data['banking_according_total'][$users_data['parent_id']] = $this->banking_report->banking_according_total($get,$users_data['parent_id']);
      }else{
        $branch_ids=$get['branch_id'];
      }
      
      $data['branch_bank_collection_list']='';
      //
      
     // print_r($data['self_branch_bank_list']);die;
      if(!empty($branch_ids))
      {
        $data['branch_bank_collection_list'] = $this->banking_report->banking_report_list_all_branch($get,$branch_ids);

         if($get['branch_id']=='all'){
            $branch_id = implode(',', $branch_ids); 
             $new_branch_ids=explode(',', $branch_id);

                if(isset( $new_branch_ids)){
            foreach($new_branch_ids as $ids){
              $data['banking_according_total'][$ids] = $this->banking_report->banking_according_total($get,$ids);
            }
          }
            }else{
            $new_branch_ids=$get['branch_id'];
            $data['banking_according_total'][ $new_branch_ids] = $this->banking_report->banking_according_total($get, $new_branch_ids);
            }


          
           //print '<pre>'; print_r($data['banking_according_total']);die;
        
      }
     // print '<pre>';print_r($data);die;
      //print '<pre>';print_r($data['branch_bank_collection_list']);die;
        $data['get'] = $get;
       $data['parent_id']=$users_data['parent_id'];
        $this->load->view('canteen/banking_report/pdf_banking_report_html',$data); 
    }
   
}
?>