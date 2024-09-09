<?php 
class Healthcloud {

    
    private static $APP_KEY = '975f4de3455fa384b4d83d571e4955b';
    private static $APP_ID = 'd963cf453';
   
   function __construct() {

    }
    
    public function check_video() 
    {
        //$developers_url = 'https://videocon-dot-kaisehain01-prod02.appspot.com/teleconserver/';
        $developers_url = 'https://videocon-dot-kaisehain01-prod.appspot.com/teleconserver/registerApt';
        
        $fields = array("patient_name"=>"Nitesh Shroff", "patient_unique_id"=>"pid123087", "patient_phone"=>"7676770921",
"patient_country_code"=> "91", "patient_email"=> "nitesh@healthcloudai.com", "doctor_name"=>"Dr. Rakesh Kumar",
"doctor_display_info"=>"MD, Neurologist and Consultant Physician (12 years)","hospital"=>"Apollo Jubilee", "appointment_utc_time"=>"1589882807589");
        
        $headers = array(
            'Header-APP_KEY=' . self::$APP_KEY,
            'Header-APP_ID=' . self::$APP_ID,
            'Content-Type:application/json'
        ); 
       
        
     // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $developers_url); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        // Close connection      
        curl_close($ch);
        
        echo "<pre>";print_r($result); exit();
        return $result;
    }
    
    public function get_video_url($fields='') 
    {
        //$developers_url = 'https://videocon-dot-kaisehain01-prod02.appspot.com/teleconserver/registerApt';
        $developers_url = 'https://videocon-dot-kaisehain01-prod.appspot.com/teleconserver/registerApt';
        
        
        $headers = array(
            'Header-APP_KEY=' . self::$APP_KEY,
            'Header-APP_ID=' . self::$APP_ID,
            'Content-Type:application/json'
        ); 
       
        
     // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $developers_url); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post   
        $result = curl_exec($ch); 
        // Close connection      
        curl_close($ch);
        
        //echo "<pre>";print_r($result); exit();
        return $result;
    }

  
 }
?>