<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_master/test_upload_model','test_master');
        $this->load->library('form_validation');
    }
    
    public function interpretation()
    {       
          $this->test_master->interpretation();  
          $response = "Test successfully Downloaded successfully.";
          echo $response;
        
    }
    
    public function downloadall($bid="",$tid="")
    {       
          $this->test_master->download_test($bid,$tid);  
          $response = "Test successfully Downloaded successfully.";
          echo $response;
    }
    
    public function downloadprofile($bid="")
    {       
          $this->test_master->download_profile($bid);  
          $response = "Profile successfully Downloaded successfully.";
          echo $response;
    }
}
?>