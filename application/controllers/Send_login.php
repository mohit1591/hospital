<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Send_login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('send_login/send_login_model','send_login');
		$this->load->library('form_validation');
	}


	 public function add()
   {
        //unauthorise_permission('1','1');
        $data['page_title'] = "Send Login credential";  
        $sms_format = sms_template_format(18);
         //echo "<pre>"; print_r($sms_format); exit;
        //$email_template = get_email_template(18);
        //$template = $email_template->template;  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'password'=>"",
                                  //'template'=>$template,
                                  'sms_template'=>$sms_format->template,
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->send_login->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->load->view('login_credential/add',$data);       
    }
    
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('password', 'password', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'password'=>$post['password'],
                                        //'template'=>$post['template'],
                                        'sms_template'=>$post['sms_template'],
                                        'status'=>$post['status']
                                       ); 
            //echo "<pre>"; print_r($data['form_data']);
            return $data['form_data'];
        }   
    }
 
    
 
}
?>