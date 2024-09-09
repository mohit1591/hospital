<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Send_whats_app extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    
    public function send_sms()
    {
        require APPPATH . '/libraries/WhatsAppApi.php';
        
        //require_once ('vendor/autoload.php'); // if you use Composer
        //require_once('ultramsg.class.php'); // if you download ultramsg.class.php
        
        $ultramsg_token="vgnxbb4xfi0mnjcm"; // Ultramsg.com token
        $instance_id="instance3710"; // Ultramsg.com instance id
        $client = new WhatsAppApi($ultramsg_token,$instance_id);
        
        $to="+917355231055"; 
        $body="Hello world test hi"; 
        $api=$client->sendChatMessage($to,$body);
        print_r($api);
    }

}