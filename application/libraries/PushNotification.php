<?php 
class PushNotification {    
    private static $API_SERVER_KEY = 'AAAA2olgyFM:APA91bGL_lnhJkeqYm0C17dTmaylS8LtUdkjwNNKNIdTfw5JxDLDTA3cr8pXOtnxgqQ_Mkktvu2lQtG00hS7j1IhIT3o8YloZ2L7533N70iNHwn9Opct70LZBFitxrzjfhZYQ6YzFGp0';
    

    private static $API_ACCESS_KEY ='AAAA11Rm8xM:APA91bErEQOkmJTRO6KK7YGTMWc2-vWNi-wX7N29HXzEyAceEY_34KrkibDVoh_uEhDx5hSiYrqMCaQbc-CwKAt2VFanSyHAooER0rRw69Mk2NxdfI1PzVD8MG9tQK4E1nZJkBpiynVK';
    private static $is_background = "TRUE";
    public function __construct() {     
     
    }
    public function sendPushNotificationToFCMSever($token, $message, $notifyID) {
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
 
        $fields = array(
            'registration_ids' => $token,
            'priority' => 10,
            'notification' => array('title' => 'HMAS', 'body' =>  $message ,'sound'=>'Default','image'=>'Notification Image' ),
        );
        $headers = array(
            'Authorization:key=' . self::$API_SERVER_KEY,
            'Content-Type:application/json'
        );  
         
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
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
        return $result;
    }
    
    
    
        public function sendPushNotificationToAndroidSever($token, $message, $notifyID) {
   
        $path_to_firebase_cm1 = 'https://fcm.googleapis.com/fcm/send';
 
        $fields = array(
            'registration_ids' => $token,
            'priority' => 10,
            'notification' => array('title' => 'HMAS', 'body' =>  $message ,'sound'=>'Default','image'=>'Notification Image' ),
        );
        $headers = array(
            'Authorization:key=' . self::$API_ACCESS_KEY,
            'Content-Type:application/json'
        );
        //echo "<pre>";print_r($headers);
     // echo $path_to_firebase_cm; die;
        // Open connection  
        $ch = curl_init(); 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm1); 
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
        return $result;
    }
 }
?>