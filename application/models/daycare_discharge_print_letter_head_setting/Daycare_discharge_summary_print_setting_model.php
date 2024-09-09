<?php
class Daycare_discharge_summary_print_setting_model extends CI_Model 
{
  var $table = 'path_print_report_setting'; 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
 
  public function get_master_unique() //doctor_signature_position
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_daycare_discharge_report_print_letter_head_setting.*');
   $this->db->where('hms_daycare_discharge_report_print_letter_head_setting.branch_id = "'.$user_data['parent_id'].'"');
    $this->db->from('hms_daycare_discharge_report_print_letter_head_setting');  
    $query = $this->db->get(); 
    return $query->result();
  }
  
  public function save()
  {  
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post();
    //print_r($post);die;
    if(!empty($post['data']))
    {    
      $current_date = date('Y-m-d H:i:s');

      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->delete('hms_daycare_discharge_report_print_letter_head_setting');
      //$message = $post['message']; 
            $header_print = 0;
            $header_pdf= 0;
            $details_print = 0;
            $details_pdf= 0;
            $middle_print = 0;
            $middle_pdf= 0;
            $footer_print = 0;
            $footer_pdf= 0;
         

      if(isset($post['header_print']))
      {
        $header_print = $post['header_print']; 
      }
      if(isset($post['header_pdf']))
      {
        $header_pdf = $post['header_pdf']; 
      }
      if(isset($post['details_print']))
      {
        $details_print = $post['details_print']; 
      }
      if(isset($post['details_pdf']))
      {
        $details_pdf = $post['details_pdf']; 
      }
      if(isset($post['middle_print']))
      {
        $middle_print = $post['middle_print']; 
      }
      if(isset($post['middle_pdf']))
      {
        $middle_pdf = $post['middle_pdf']; 
      }
      if(isset($post['footer_print']))
      {
        $footer_print = $post['footer_print']; 
      }
      if(isset($post['footer_pdf']))
      {
        $footer_pdf = $post['footer_pdf']; 
      }
      // if(isset($post['doctor_signature_position']))
      // {
      //   $doctor_signature_position = $post['doctor_signature_position']; 
      // }
      // if(isset($post['signature_text']))
      // {
      //   $doctor_signature_text = $post['signature_text']; 
      // }
      // if(isset($post['method']))
      // {
      //   $method = $post['method']; 
      // }
      // if(isset($post['sample_type']))
      // {
      //   $sample_type = $post['sample_type']; 
      // }
      if(isset($post['pixel_value']))
      {
        $pixel_value = $post['pixel_value']; 
      }
      

            foreach($post['data'] as $key=>$val)
            {
              $data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "page_header"=>$post['messageh'],
                               "page_details"=>$post['messaged'],
                               "page_middle"=>$post['messagem'],
                               "page_footer"=>$post['messagef'],
                               "header_print"=>$header_print,
                               "header_pdf"=>$header_pdf,
                               // "doctor_signature_position"=>$doctor_signature_position,
                               // 'doctor_signature_text'=>$doctor_signature_text,
                               // "method"=>$method,
                               // "sample_type"=>$sample_type,
                                "pixel_value"=>$pixel_value,
                               "details_print"=>$details_print,
                               "details_pdf"=>$details_pdf,
                               "middle_print"=>$middle_print,
                               "middle_pdf"=>$middle_pdf,
                               "footer_print"=>$footer_print,
                               "footer_pdf"=>$footer_pdf,
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
                         );

              $this->db->insert('hms_daycare_discharge_report_print_letter_head_setting',$data);
    
     
            } 
    //echo $this->db->last_query();die;
    } 
  }
 
    
} 
?>
<?php
/*
class Test_report_print_setting_model extends CI_Model 
{
  var $table = 'path_print_report_setting'; 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
 
  public function get_master_unique()
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('path_print_report_setting.*, discharge_report_print_letter_head_setting.setting_name, discharge_report_print_letter_head_setting.setting_value, discharge_report_print_letter_head_setting.page_header, discharge_report_print_letter_head_setting.page_details, discharge_report_print_letter_head_setting.page_middle, discharge_report_print_letter_head_setting.page_footer,
            discharge_report_print_letter_head_setting.header_print,
            discharge_report_print_letter_head_setting.header_pdf,
            discharge_report_print_letter_head_setting.details_print,
            discharge_report_print_letter_head_setting.details_pdf,
            discharge_report_print_letter_head_setting.middle_print,
            discharge_report_print_letter_head_setting.middle_pdf,
            discharge_report_print_letter_head_setting.footer_print,
            discharge_report_print_letter_head_setting.footer_pdf,discharge_report_print_letter_head_setting.doctor_signature_position
      ');
    $this->db->join('discharge_report_print_letter_head_setting', 'discharge_report_print_letter_head_setting.unique_id=path_print_report_setting.id AND discharge_report_print_letter_head_setting.branch_id = "'.$user_data['parent_id'].'"','left');
    $this->db->from('path_print_report_setting');  
    $query = $this->db->get(); 
    return $query->result();
  }
  
  public function save()
  {  
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post(); 
   
    if(!empty($post['data']))
    {    
      $current_date = date('Y-m-d H:i:s');

      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->delete('discharge_report_print_letter_head_setting');
      //$message = $post['message']; 
            $header_print = 0;
            $header_pdf= 0;
            $details_print = 0;
            $details_pdf= 0;
            $middle_print = 0;
            $middle_pdf= 0;
            $footer_print = 0;
            $footer_pdf= 0;
            $doctor_signature_position= 0;
      if(isset($post['header_print']))
      {
        $header_print = $post['header_print']; 
      }
      if(isset($post['header_pdf']))
      {
        $header_pdf = $post['header_pdf']; 
      }
      if(isset($post['details_print']))
      {
        $details_print = $post['details_print']; 
      }
      if(isset($post['details_pdf']))
      {
        $details_pdf = $post['details_pdf']; 
      }
      if(isset($post['middle_print']))
      {
        $middle_print = $post['middle_print']; 
      }
      if(isset($post['middle_pdf']))
      {
        $middle_pdf = $post['middle_pdf']; 
      }
      if(isset($post['footer_print']))
      {
        $footer_print = $post['footer_print']; 
      }
      if(isset($post['footer_pdf']))
      {
        $footer_pdf = $post['footer_pdf']; 
      } 
      if(isset($post['doctor_signature_position']))
      {
        $doctor_signature_position = $post['doctor_signature_position']; 
      } 

            foreach($post['data'] as $key=>$val)
            {
              $data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               "setting_name"=>$val['setting_name'],
                               "page_header"=>$post['messageh'],
                               "page_details"=>$post['messaged'],
                               "page_middle"=>$post['messagem'],
                               "page_footer"=>$post['messagef'],
                               "header_print"=>$header_print,
                               "doctor_signature_position"=>$doctor_signature_position,
                               "header_pdf"=>$header_pdf,
                               "details_print"=>$details_print,
                               "details_pdf"=>$details_pdf,
                               "middle_print"=>$middle_print,
                               "middle_pdf"=>$middle_pdf,
                               "footer_print"=>$footer_print,
                               "footer_pdf"=>$footer_pdf,
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
                         );
              $this->db->insert('discharge_report_print_letter_head_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
    } 
  }
 
    
} */
?>
