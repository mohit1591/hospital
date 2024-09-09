<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Print_letterhead_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('print_letterhead_setting/print_letterhead_setting_model','print_letterhead_setting');
        $this->load->library('form_validation');
    }

    public function template($type='')
    {     
        //$type=1;
        //if($type==1) 
        $data['page_title'] = 'Print Letter Head Setting';
        /*if($type==2) 
        $data['page_title'] = 'Receipt Letter Head Print Settings';
        if($type==3) 
        $data['page_title'] = 'Prescription Letter Head Print Settings';
        
        if($type==4) 
        $data['page_title'] = 'Medicine Letter Head Print Settings'; 
        if($type==5) 
        $data['page_title'] = 'Medicine Sale Letter Head Print Settings'; 
        if($type==6) 
        $data['page_title'] = 'Medicine Indent Letter Head Print Settings';*/ 
        
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->print_letterhead_setting->save($type);
            
             if($type==1) 
             echo 'OPD Letter Head Print Settings successfully updated.';
             if($type==2) 
             echo 'Receipt Letter Head Print Settings successfully updated.';
             if($type==3) 
             echo 'Prescription Letter Head Print Settings successfully updated.';
             if($type==4) 
             echo 'Medicine Letter Head Print Settings successfully updated.';
             if($type==5) 
             echo 'Medicine sale Letter Head Print Settings successfully updated.';
          if($type==6) 
             echo 'Medicine Indent Letter Head Print Settings successfully updated.';
            return false;
        }
        $data['report_print_setting'] = $this->print_letterhead_setting->get_master_unique($type);
        
        if($type==4 || $type==5 || $type==6)
        {
            $this->load->view('print_letterhead_setting/add_medicine_purchase',$data); 
        }
        else{
            $this->load->view('print_letterhead_setting/add',$data); 
        }
       // $this->load->view('print_letterhead_setting/add',$data);
    } 

  /*  public function test_receipt()
    {     
        $type=2;
        $data['page_title'] = 'Receipt Letter Head Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->print_letterhead_setting->save($type);
            echo 'Receipt Letter Head Print Settings successfully updated.';
            return false;
        }
        $data['report_print_setting'] = $this->print_letterhead_setting->get_master_unique($type);
        $this->load->view('print_letterhead_setting/add',$data);
    }

    public function prescription()
    {     
        $type=3;
        $data['page_title'] = 'Prescription Letter Head Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->print_letterhead_setting->save($type);
           
            return false;
        }
        $data['report_print_setting'] = $this->print_letterhead_setting->get_master_unique($type);
        $this->load->view('print_letterhead_setting/add',$data);
    } 
*/
}
?>