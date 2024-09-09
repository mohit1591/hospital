<?php
class Print_letterhead_setting_model extends CI_Model 
{
  var $table = 'hms_print_letter_head_template_setting'; 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }
 
  public function get_master_unique($type) //doctor_signature_position
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_print_letter_head_template_setting.*');
    $this->db->where('hms_print_letter_head_template_setting.branch_id = "'.$user_data['parent_id'].'"');
    $this->db->where('hms_print_letter_head_template_setting.unique_id = "'.$type.'"');
    $this->db->from('hms_print_letter_head_template_setting');  
    $query = $this->db->get(); 
   // echo $this->db->last_query();die();
    return $query->result();
  }
  
  public function save($type)
  {  
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post();
    if(!empty($post['data']))
    {    
      $current_date = date('Y-m-d H:i:s');

      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('unique_id',$type);
      $this->db->delete('hms_print_letter_head_template_setting');
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
     
      if(isset($post['pixel_value']))
      {
        $pixel_value = $post['pixel_value']; 
      }
            foreach($post['data'] as $key=>$val)
            {
              $data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$type,
                               "setting_name"=>$val['setting_name'],
                               "page_header"=>$post['messageh'],
                               "page_details"=>$post['messaged'],
                               "page_middle"=>$post['messagem'],
                               "page_footer"=>$post['messagef'],
                               "header_print"=>$header_print,
                               "header_pdf"=>$header_pdf,
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

              $this->db->insert('hms_print_letter_head_template_setting',$data);
         } 
      } 
  }  
} 
?>
